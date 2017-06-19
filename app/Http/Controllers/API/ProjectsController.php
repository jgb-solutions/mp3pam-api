<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Cache, Auth, HP, Storage;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

class ProjectsController extends Controller
{
		public function __construct()
		{
			$this->middleware(['auth', 'active'])->except(['index', 'show']);
		}

	public function index()
	{
		return view('projects.index', [
			'title' => trans('text.pages.projects.title'),
			'projects' => Project::with('category')->latest()->simplePaginate(15),
			'page' => request('page') ? '('.trans('projects.page') . ' ' . request('page') .')' : ''
		]);
	}

	public function show($slug)
	{
		$key = 'project_' . $slug;

		$data = Cache::rememberForever($key, function() use ($slug) {
			$project = Project::withCount('funders')->with('category', 'user')->whereSlug($slug)->firstOrFail();

			$related = Project::related($project)->take(4)->get();

			return [
				'project' => $project,
				'related' => $related,
			];
		});

		$data['title'] = $data['project']->name;

		return view('projects.show', $data);
	}

	public function getCreateForm()
	{
		$categories = Cache::rememberForever(config('site.cacheKey.allCategories'), function() {
			return Category::by('name')->get();
		});

		return view('projects.create', [
			'title' => trans('projects.add'),
			'categories' => $categories,
			'user' => auth()->user()
		]);
	}

	public function create(CreateProjectRequest $request)
	{
		$project = $request->user()->projects()->create([
			'name'          => $request->name,
			'slug'          => str_slug($request->name),
			'category_id'   => $request->category_id,
			'description'   => $request->description,
			'budget'        => $request->budget,
			'doc'           => HP::store($request->file('doc'))
		]);

		Cache::forget('pages.home');

		return redirect(HP::route('project.show', $project->slug))
			->withTitle(trans('message.projectCreated.title'))
			->withText(trans('message.projectCreated.text'))
			->withStatus('success')
			->withButtonColor('#5cb85c')
			->withButtonText(trans('text.ok'));
	}

	public function getEditForm(project $project)
	{
		$key = 'allCategories';

		$categories = Cache::rememberForever($key, function() {
			return Category::by('name')->get();
		});

		return view('projects.edit', [
			'title' => trans('projects.edit') . ' "' . $project->name.'"',
			'project' => $project,
			'categories' => $categories,
			'user' => auth()->user()
		]);
	}

	public function update(UpdateprojectRequest $request, Project $project)
	{
		$key = 'project_' . $project->slug;

		$project->name          = $request->name;
		$project->slug          = str_slug($request->name);
		$project->category_id   = $request->category_id;
		$project->description   = $request->description;
		$project->budget        = $request->budget;

		if ($request->hasFile('logo')) {
			Storage::delete($project->logo); // Delete the current logo before adding a new one
			// $project->logo = HP::store($request->file('logo'));
			$project->logo = HP::image($request->file('logo'), 500, null);
		}

		if ($request->hasFile('doc')) {
			Storage::delete($project->doc); // Delete the current doc before adding a new one
			$project->doc = HP::store($request->file('doc'));
		}

		$project->save();

		Cache::forget('pages.home');
		Cache::forget($key);
		// Cache::forget('allSponsors');

		return redirect($project->url)
			->withTitle(trans('message.projectUpdated.title'))
			->withText(trans('message.projectUpdated.text'))
			->withType('success')
			// ->withButtonColor('#FF9B22')
			->withButtonText(trans('text.ok'));
	}

	public function delete(project $project)
	{
		$key = 'project_' . $project->slug;

		$this->authorize('delete', $project);

		$project->delete();

		// Cache::forget('pages.home');
		Cache::forget($key);
		// Cache::forget('allSponsors');

		return redirect(route('myAccount.projects'))
			->withTitle(trans('message.projectDeleted.title'))
			->withText(trans('message.projectDeleted.text'))
			->withType('success')
			// ->withButtonColor('#FF9B22')
			->withButtonText(trans('text.ok'));
	}
}
