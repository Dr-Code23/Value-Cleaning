<?php

namespace Modules\Service\Http\Controllers\User;

use Illuminate\Http\Request;
use Modules\Service\Entities\Portfolio;
use Modules\Service\Http\Requests\PortfolioRequest;
use Modules\Service\Transformers\PortfolioResource;
use Illuminate\Routing\Controller;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::get();
        return ['statusCode' => 200, 'status' => true,
            'data' => PortfolioResource::collection($portfolios)
        ];
    }

    public function store(PortfolioRequest $request)
    {
        $portfolio = new Portfolio;
        $portfolio->title = $request->input('title');
        $portfolio->description = $request->input('description');
        if ($request['gallery']) {
            foreach ($request->gallery as $gallery) {
                $portfolio->addMedia($gallery)->toMediaCollection('portfolio');
            }
        }
        $portfolio->save();
        $portfolio->with('media');
        return new PortfolioResource($portfolio);
    }

    public function show($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        return new PortfolioResource($portfolio);
    }

    public function update(PortfolioRequest $request, $id)
    {
        $portfolio = Portfolio::findOrFail($id);
        $portfolio->title = $request->input('title');
        $portfolio->description = $request->input('description');
        $portfolio->save();
        if ($request['gallery']) {
            $portfolio->media()->delete();
            foreach ($request->gallery as $gallery) {
                $portfolio->addMedia($gallery)->toMediaCollection('portfolio');
            }
            $portfolio->save();
        }
        return new PortfolioResource($portfolio);
    }

    public function destroy($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        $portfolio->delete();

        return response()->json(null, 204);
    }
}
