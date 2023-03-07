<?php

namespace Modules\Favorite\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Favorite\Repositories\Interfaces\FavoriteRepositoryInterface;

class FavoriteController extends Controller
{
    private $FavoriteRepository;

    public function __construct(FavoriteRepositoryInterface $FavoriteRepository)
    {
        $this->FavoriteRepository = $FavoriteRepository;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return $this->FavoriteRepository->index();
    }

    public function store(Request $request)
    {
        return $this->FavoriteRepository->store($request);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return$this->FavoriteRepository->show($id);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        return $this->FavoriteRepository->destroy($id);
    }
}
