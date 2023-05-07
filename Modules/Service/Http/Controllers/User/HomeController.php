<?php

namespace Modules\Service\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Announcement\Entities\Announcement;
use Modules\Announcement\Transformers\AnnouncementResource;
use Modules\Auth\Entities\SendNotification;
use Modules\Category\Entities\SubSubCategory;
use Modules\Category\Transformers\CategoryResource;
use Modules\Order\Entities\Order;
use Modules\Requirement\Entities\Requirement;
use Modules\Requirement\Transformers\RequirementResource;
use Modules\Review\Entities\Review;
use Modules\Service\Entities\Portfolio;
use Modules\Service\Entities\Service;
use Modules\Service\Entities\SubService;
use Modules\Service\Transformers\PortfolioResource;
use Modules\Service\Transformers\ServiceResource;
use Modules\Service\Transformers\SubServiceResource;

class HomeController extends Controller
{

    public function userHome()
    {
        $Service = Service::where('active', 1)->get();
        $categories = SubSubCategory::latest()->get();
        $announcement = Announcement::latest()->get();
        $portfolios = Portfolio::all();
        $notification = SendNotification::where('is_read', false)->count();
        return response()->json(['notification' => $notification ?? 0, 'portfolio' => PortfolioResource::collection($portfolios), "announcement" => AnnouncementResource::collection($announcement), "Service" => ServiceResource::collection($Service), "categories" => CategoryResource::collection($categories)]);

    }

    public function serviceDetails($id)
    {
        $SubService = Service::find($id);
        $rate = Review::where('service_id', $id)->avg('star_rating');

        return ['statusCode' => 200,
            'status' => true,
            'data' => new ServiceResource($SubService),
            'rate' => $rate];
    }

    public function subService($id)
    {
        $SubService = SubService::where('service_id', $id)->get();
        return ['statusCode' => 200,
            'status' => true,
            'data' => SubServiceResource::collection($SubService)];
    }

    public function requirement($id)
    {
        $requirement = Requirement::where('service_id', $id)->get();
        return ['statusCode' => 200,
            'status' => true,
            'data' => RequirementResource::collection($requirement)];
    }


    public function topServices(Request $request)
    {
        if ($request->q) {
            $services = Service::where("title", "like", "%$request->q%")
                ->orderBy("id", "DESC")
                ->get();
            return ['statusCode' => 200, 'status' => true, 'data' => ServiceResource::collection($services)];

        } elseif ($request->category_id) {
            $services = Service::query()
                ->join('categories', 'services.category_id', '=', 'categories.id')
                ->where('categories.title', "like", "%$request->category_id%")
                ->select('services.*')
                ->get();

            return ['statusCode' => 200, 'status' => true, 'data' => ServiceResource::collection($services)];

        } else {
            $skus = Order::selectRaw('COUNT(*)')
                ->whereColumn('service_id', 'services.id')
                ->getQuery();
            $services = Service::select('*')
                ->selectSub($skus, 'skus_count')
                ->orderBy('skus_count', 'DESC')->get();
            return ['statusCode' => 200, 'status' => true, 'data' => ServiceResource::collection($services)];

        }

    }

    public function jobDone($id)
    {
        $job_done = Order::where(["service_id" => $id, 'status' => 'finished'])->count();
        return ['statusCode' => 200, 'status' => true, 'job_done' => $job_done];
    }

    public function portfolio()
    {
        $portfolios = Portfolio::all();
        return PortfolioResource::collection($portfolios);
    }
}
