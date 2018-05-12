<?php

namespace App\Http\Controllers\API;

use Str;
use Auth;
use Cache;
use Validator;
use App\Models\User;
use App\Models\Music;
use App\Http\Requests;
use App\Models\Category;
use App\Helpers\MP3Pam;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MusicCollection;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\UpdateCategoryRequest;

class CategoriesController extends Controller
{

	public function index()
	{
		return Category::allCategories();
	}

	public function getCreate()
	{
		$data = [
			'categories' => Category::remember(999, 'allCategories')->byName()->get(),
			'title' => 'Kreye Yon Nouvo Kategori'
		];

		return view('cats.create', $data);
	}

	public function postCreate(Request $request)
	{
		$rules = ['name' => 'required', 'slug' => 'required'];

		$messages = [
			'name.required' => config('site.validate.name.required'),
			'slug.required' => config('site.validate.slug.required')
		];

		$validator = Validator::make( $request->all(), $rules, $messages );

		if ($validator->fails()) {
			return redirect(route('admin.cat'))
				->withErrors($validator);
		}

		Category::create([
			'name' => $request->get('name'),
			'slug' => Str::slug($request->get('slug'))
		]);

		Cache::forget('allCategories');

		return redirect(route('admin.cat'));
	}

	public function show($slug)
	{
	   $category= Category::withCount('musics')->whereSlug($slug)->firstOrFail();

		$musics = $category->musics()->with('artist', 'category')->latest()->paginate(10);

		return new MusicCollection($musics);
	}

	public function musics($slug)
	{
		if (request()->has('page')) {
			$page = request()->get('page');
		} else {
			$page = 1;
		}

		$key = "category_musics_" . $slug . $page;

		$data = Cache::rememberForever($key, function() use ($slug) {
		   $cat = Category::with([
				'musics' => function($query) {
					$query->published()->latest();
				}
			])
			->whereSlug($slug)->first();

			return [
				'cat' 	=> $cat,
				'musics' 	=> $cat->musics()->paginate(24),
				// 'musics' 	=> $cat->musics()->published()->latest()->paginate(10),
				'title' => $cat->name
			];
		});

		return view('cats.music', $data);
	}

	public function edit(Category $category)
	{
		return view('cats.edit', [
			'category' 	 => $category,
			'categories' => Cache::get('allCategories', Category::byName()->get()),
		]);
	}

	public function update(UpdateCategoryRequest $request, Category $category)
	{
		$category->name = $request->get('name');
		$category->slug = str_slug($request->get('slug'));
		$category->save();

		Cache::forget('allCategories');

		return redirect(route('admin.cat'));
	}

	public function destroy($id)
	{
		Category::destroy($id);

		Cache::forget('allCategories');

		return back();
	}
}