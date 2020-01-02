<?php

  namespace App\Http\Controllers\API;

  use App\Models\Artist;
  use Carbon\Carbon;
  use App\Models\Album;
  use App\Models\Track;
  use Illuminate\Http\Request;
  use Suin\RSSWriter\Feed;
  use Suin\RSSWriter\Item;
  use Suin\RSSWriter\Channel;
  use App\Http\Controllers\Controller;

  class FeedController extends Controller
  {
    public function __invoke(Request $request, $type)
    {
      // $key = $type . '_rss_feed_';

      switch ($type) {
        case 'track':
          $objs = Track::latest()->with('genre', 'artist')->take(30)->get();

          $hash = 'Track';
          break;

        case 'album':
          $objs = Album::latest()->with('artist')->take(30)->get();

          $hash = 'Album';
          break;

        case 'artist':
          $objs = Artist::latest()->take(30)->get();
          break;
      }

      $rss = $this->buildRssData($objs, $type);

      $rss = response($rss)->header('Content-type', 'application/rss+xml');

      return $rss;
    }

    /**
     * Return a string with the feed data
     *
     * @return string
     */
    protected function buildRssData($objs, $type)
    {
      $now     = Carbon::now();
      $feed    = new Feed();
      $channel = new Channel();

      $channel->title(config('site.name'))
        ->description(config('site.description'))
        ->url(config('site.url'))
        ->language('en')
        ->copyright(date('Y') . ' ' . config('site.name') . ', All Rights Reserved.')
        ->lastBuildDate($now->timestamp)
        ->appendTo($feed);

      foreach ($objs as $obj) {
        $item = new Item();

        switch ($type) {
          case 'track':
            $title       = "#newTrack {$obj->title} by {$obj->artist->stage_name}";
            $url         = "https://mp3pam.com/track/{$obj->hash}";
            $description = "Listen to {$obj->title} by {$obj->artist->stage_name} on " . config('site.name');
            break;

          case 'album':
            $title       = "#newAlbum {$obj->title} (album) by {$obj->artist->stage_name}";
            $url         = "https://mp3pam.com/album/{$obj->hash}";
            $description = "Listen to {$obj->title} by {$obj->artist->stage_name} on " . config('site.name');
            break;

          case 'artist':
            $title       = "#newArtist {$obj->stage_name} on " . config('site.name');
            $url         = "https://mp3pam.com/artist/{$obj->hash}";
            $description = "Listen to {$obj->stage_name} on " . config('site.name');
            break;
        }

        $item->title($title)
          ->description($description)
          ->url($url)
          ->pubDate($obj->created_at->timestamp)
          ->guid($url, true)
          ->appendTo($channel);
      }

      $feed = (string)$feed;

      // Replace a couple items to make the feed more compliant
      $feed = str_replace(
        '<rss version="2.0">',
        '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">',
        $feed
      );

      $feed = str_replace(
        '<channel>',
        '<channel>
			<atom:link href="' . secure_url(config('site.url') . "/feed/$type") . '" rel="self" type="application/rss+xml" />',
        $feed
      );

      return $feed;
    }
  }