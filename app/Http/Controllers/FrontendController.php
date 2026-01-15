<?php

namespace App\Http\Controllers;

use App\Models\Advantage;
use App\Models\Amenity;
use App\Models\Blog;
use App\Models\FrontHomePage;
use App\Models\Notification;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Crypt;

class FrontendController extends Controller
{
    public function themePage($code = null)
    {
        $user = User::where('code', $code)->firstOrFail();
        $settings = settingsById($user->id);
        $parent_id = $user->id;

        $allAmenities = Amenity::where('parent_id', $user->id)->get();

        $listingTypes = Property::where('parent_id', $user->id)
            ->whereIn('listing_type', ['sell', 'rent'])
            ->select('listing_type')
            ->distinct()
            ->pluck('listing_type')
            ->toArray();

        $propertiesByType = Property::where('parent_id', $user->id)
            ->whereIn('listing_type', $listingTypes)
            ->get()
            ->groupBy('listing_type');

        return view('theme.index', compact('settings', 'parent_id', 'user', 'allAmenities', 'listingTypes', 'propertiesByType'));
    }


    public function searchLocation(Request $request, $code)
    {
        $locationSlug = $request->input('location');

        if (!$locationSlug) {
            return redirect()->back()->with('error', 'Location not selected.');
        }

        return redirect()->route('location.home', ['code' => $code]) . '?location=' . $locationSlug;
    }


    public function index()
    {
        if (!Auth::user()->can('manage front home page')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
        $loginUser = Auth::user();
        $frontHomePage = FrontHomePage::where('parent_id', '=', parentId())->get();

        return view('front-home.index', compact('loginUser', 'frontHomePage'));
    }

    public function update(Request $request, FrontHomePage $homePage, $id)
    {

        if (!Auth::user()->can('edit front home page')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        $homePage = FrontHomePage::find($id);
        $old_content_value = '';
        if (!empty($request->content_value)) {
            $old_content_value = json_decode($homePage->content_value, true);
        }
        $content_value = $request->content_value;

        /* section 0 */
        if ($request->tab == 'profile_tab_1') {
            if (!empty($request->content_value['banner_image1'])) {
                $banner_image1 = $request->content_value['banner_image1'];
                $filenameWithExt = $banner_image1->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $banner_image1->getClientOriginalExtension();
                $fileNameToStore = $filename . '_banner_image1_' . date('Ymdhisa') . '.' . $extension;

                $dir = storage_path('upload/fronthomepage/');
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }

                $banner_image1->storeAs('upload/fronthomepage/', $fileNameToStore);
                $content_value['banner_image1_path'] = 'upload/fronthomepage/' . $fileNameToStore;
            } else {
                $content_value['banner_image1_path'] = !empty($old_content_value['banner_image1_path']) ? $old_content_value['banner_image1_path'] : '';
            }
        }

        /* section 1 */
        if ($request->tab == 'profile_tab_2') {
            for ($is4 = 1; $is4 <= 4; $is4++) {
                if (!empty($request->content_value['Sec1_box' . $is4 . '_image'])) {
                    $box_image_path = $request->content_value['Sec1_box' . $is4 . '_image'];
                    $filenameWithExt = $box_image_path->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $box_image_path->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_Section_4_image_' . $is4 . date('Ymdhisa') . '.' . $extension;

                    $dir = storage_path('upload/fronthomepage/');
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }

                    $box_image_path->storeAs('upload/fronthomepage/', $fileNameToStore);
                    $content_value['Sec1_box' . $is4 . '_image_path'] = 'upload/fronthomepage/' . $fileNameToStore;
                } else {
                    $content_value['Sec1_box' . $is4 . '_image_path'] = !empty($old_content_value['Sec1_box' . $is4 . '_image_path']) ? $old_content_value['Sec1_box' . $is4 . '_image_path'] : '';
                }
            }
        }

        /* section 2 */
        // if ($request->tab == 'profile_tab_3') {
        //     for ($i = 1; $i <= 4; $i++) {
        //         if (!empty($request->content_value['box' . $i . '_number_image'])) {
        //             $box_image_path = $request->content_value['box' . $i . '_number_image'];
        //             $filenameWithExt = $box_image_path->getClientOriginalName();
        //             $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        //             $extension = $box_image_path->getClientOriginalExtension();
        //             $fileNameToStore = $filename . '_box_image_path_' . $i . date('Ymdhisa') . '.' . $extension;

        //             $dir = storage_path('upload/fronthomepage/');
        //             if (!file_exists($dir)) {
        //                 mkdir($dir, 0777, true);
        //             }

        //             $box_image_path->storeAs('upload/fronthomepage/', $fileNameToStore);
        //             $content_value['box_image_' . $i . '_path'] = 'upload/fronthomepage/' . $fileNameToStore;
        //         } else {
        //             $content_value['box_image_' . $i . '_path'] = !empty($old_content_value['box_image_' . $i . '_path']) ? $old_content_value['box_image_' . $i . '_path'] : '';
        //         }
        //     }
        // }


        /* section 4 */
        if ($request->tab == 'profile_tab_5') {
            if (!empty($request->content_value['about_image'])) {
                $about_image = $request->content_value['about_image'];
                $filenameWithExt = $about_image->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $about_image->getClientOriginalExtension();
                $fileNameToStore = $filename . '_about_image_' . date('Ymdhisa') . '.' . $extension;

                $dir = storage_path('upload/fronthomepage/');
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }

                $about_image->storeAs('upload/fronthomepage/', $fileNameToStore);
                $content_value['about_image_path'] = 'upload/fronthomepage/' . $fileNameToStore;
            } else {
                $content_value['about_image_path'] = !empty($old_content_value['about_image_path']) ? $old_content_value['about_image_path'] : '';
            }
        }


        /* section 6 */
        if ($request->tab == 'profile_tab_7') {
            if (!empty($request->content_value['banner_image2'])) {
                $banner_image2 = $request->content_value['banner_image2'];
                $filenameWithExt = $banner_image2->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $banner_image2->getClientOriginalExtension();
                $fileNameToStore = $filename . '_banner_image2_' . date('Ymdhisa') . '.' . $extension;

                $dir = storage_path('upload/fronthomepage/');
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }

                $banner_image2->storeAs('upload/fronthomepage/', $fileNameToStore);
                $content_value['banner_image2_path'] = 'upload/fronthomepage/' . $fileNameToStore;
            } else {
                $content_value['banner_image2_path'] = !empty($old_content_value['banner_image2_path']) ? $old_content_value['banner_image2_path'] : '';
            }
        }

        /* section 7 */
        if ($request->tab == 'profile_tab_8') {
            for ($is7 = 1; $is7 <= 8; $is7++) {
                if (!empty($request->content_value['Sec7_box' . $is7 . '_image'])) {
                    $box_image_path = $request->content_value['Sec7_box' . $is7 . '_image'];
                    $filenameWithExt = $box_image_path->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $box_image_path->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_Section_7_image_' . $is7 . date('Ymdhisa') . '.' . $extension;

                    $dir = storage_path('upload/fronthomepage/');
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }

                    $box_image_path->storeAs('upload/fronthomepage/', $fileNameToStore);
                    $content_value['Sec7_box' . $is7 . '_image_path'] = 'upload/fronthomepage/' . $fileNameToStore;
                } else {
                    $content_value['Sec7_box' . $is7 . '_image_path'] = !empty($old_content_value['Sec7_box' . $is7 . '_image_path']) ? $old_content_value['Sec7_box' . $is7 . '_image_path'] : '';
                }
            }
        }


        $homePage->content_value = $content_value;
        $homePage->save();
        return redirect()->back()->with('tab', $request->tab)->with('success', __('Home Page Content Updated Successfully.'));
    }


    public function blogPage(Request $request, $code)
    {
        $user = User::where('code', $code)->first();
        $settings = settingsById($user->id);
        $blogs = Blog::where('parent_id', $user->id)->latest()->paginate(4);
        if ($request->ajax()) {
            return view('theme.blogbox', compact('blogs', 'settings', 'user'))->render();
        }

        return view('theme.blog', compact('blogs', 'settings', 'user'));
    }

    // public function blogDetailPage($code, $blog)
    // {
    //     $user = User::where('code', $code)->first();
    //     $settings = settingsById($user->id);
    //     $blog = Blog::find($blog);
    //     return view('theme.blog-detail', compact('blog', 'settings', 'user'));
    // }

    public function blogDetailPage($code, $slug)
    {
        $user = User::where('code', $code)->firstOrFail();
        $settings = settingsById($user->id);
        $blog = Blog::where('slug', $slug)->firstOrFail();

        return view('theme.blog-detail', compact('blog', 'settings', 'user'));
    }




    public function propertyPage(Request $request, $code)
    {
        $user = User::where('code', $code)->firstOrFail();
        $settings = settingsById($user->id);

        $listingTypes = Property::where('parent_id', $user->id)
            ->whereIn('listing_type', ['sell', 'rent'])
            ->select('listing_type')
            ->distinct()
            ->pluck('listing_type')
            ->toArray();

        $propertyType = Property::where('parent_id', $user->id)
            ->whereIn('listing_type', $listingTypes)
            ->get()
            ->groupBy('listing_type');

        $properties = Property::where('parent_id', $user->id)
            // ->whereIn('listing_type', $listingTypes)
            ->latest()
            ->paginate(12);

        $noPropertiesMessage = $properties->isEmpty()
            ? 'No properties available with the selected filters.'
            : '';


        $countries = Property::where('parent_id', $user->id)
            ->select('country')
            ->distinct()
            ->orderBy('country')
            ->pluck('country');

        $states = Property::where('parent_id', $user->id)
            ->select('state')
            ->distinct()
            ->orderBy('state')
            ->pluck('state');

        $cities = Property::where('parent_id', $user->id)
            ->select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');


        if ($request->ajax()) {
            return view('theme.propertybox', compact('properties', 'user', 'noPropertiesMessage', 'settings', 'propertyType', 'countries', 'states', 'cities'))->render();
        }

        return view('theme.property', compact('properties', 'settings', 'user', 'propertyType', 'noPropertiesMessage', 'countries', 'states', 'cities'));
    }




    public function detailPage($code, $id)
    {

        $ids = Crypt::decrypt($id);
        $property = Property::where('id', $ids)->first();
        $units = PropertyUnit::where('property_id', $property->id)->orderBy('id', 'desc')->get();

        $user = User::where('code', $code)->firstOrFail();
        $settings = settingsById($user->id);


        $selectedAmenities = collect();
        if (!empty($property->amenities_id)) {
            $ids = array_filter(explode(',', $property->amenities_id));
            $selectedAmenities = Amenity::whereIn('id', $ids)->get();
        }

        $selectedAdvantages = collect();
        if (!empty($property->advantage_id)) {
            $ids = array_filter(explode(',', $property->advantage_id));
            $selectedAdvantages = Advantage::whereIn('id', $ids)->get();
        }


        return view('theme.detail', compact('code', 'property', 'user', 'settings', 'selectedAmenities', 'selectedAdvantages', 'units'));
    }


    public function contactPage(Request $request, $code)
    {
        $user = User::where('code', $code)->first();
        $settings = settingsById($user->id);
        return view('theme.contact', compact('settings', 'user'));
    }

    public function getStates(Request $request)
    {
        $states = Property::where('country', $request->country)
            ->distinct()
            ->pluck('state');

        return response()->json($states);
    }

    public function getCities(Request $request)
    {
        $cities = Property::where('state', $request->state)
            ->distinct()
            ->pluck('city');

        return response()->json($cities);
    }

    public function search(Request $request, $code)
    {
        $user = User::where('code', $code)->firstOrFail();
        $settings = settingsById($user->id);

        $query = Property::where('parent_id', $user->id);

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $properties = $query->paginate(12);

        $noPropertiesMessage = $properties->isEmpty()
            ? 'No properties available with the selected filters.'
            : '';

        if ($request->ajax()) {
            return view('theme.propertybox', [
                'properties' => $properties,
                'settings' => $settings,
                'user' => $user,
                 'noPropertiesMessage' => $noPropertiesMessage,

            ])->render();
        }

        return view('theme.property', compact('user', 'properties', 'settings'));
    }
}
