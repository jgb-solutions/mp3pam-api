<?php

namespace App\GraphQL\Mutations;
use App\Helpers\MP3Pam;
use App\Models\Artist;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateArtistMutation
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        extract($args['input']);

        $artist = auth()->user()->artists()->firstOrNew([
            'name' => $name,
            'stage_name' => $stage_name,
             'img_bucket' => $img_bucket,
        ]);

        if (isset($poster)) {
            $artist->poster = $poster;
        }

        if (isset($bio)) {
            $artist->bio = $bio;
        }

        if (isset($facebook)) {
            $artist->facebook = $facebook;
        }

        if (isset($twitter)) {
            $artist->twitter = $twitter;
        }
        if (isset($instagram)) {
            $artist->instagram = $instagram;
        }
        if (isset($youtube)) {
            $artist->youtube = $youtube;
        }

        if (!$artist->hash) {
            $artist->hash = MP3Pam::getHash(Artist::class);
        }

        $artist->save();

        return $artist;
    }
}
