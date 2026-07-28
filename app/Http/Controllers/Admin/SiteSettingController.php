<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SiteSettingController extends Controller
{
   
    public function index()
    {
        //
    }

   
    public function create()
    {
        //
    }

   
    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $data = SiteSetting::find($id);
        return view('admin.site_settings.all',compact('data'));
    }
    public function site()
    {
        $site = SiteSetting::first();
        return view('admin.settings.site.company',compact('site'));
    }

    public function company_setting_update(Request $request)
    {
        $site = SiteSetting::first();


        $site = $site->fill($request->input());
        // dd($site);

        if($request->hasFile('logo'))
        {
            $site->logo = $request->logo->store('site');
        }
        if($request->hasFile('site_logo'))
        {
            $site->site_logo = $request->site_logo->store('site');
        }
        if($request->hasFile('favicon'))
        {
            $site->favicon = $request->favicon->store('site');
        }
        if($request->hasFile('admin_logo'))
        {
            $site->admin_logo = $request->admin_logo->store('site');
        }
        if($request->hasFile('image'))
        {
            $site->image = $request->image->store('site');
        }

        $site->save();
        Alert::success('Updated','Successfully');
        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // public function location(Request $request)
    // {
    //     $siteSetting =SiteSetting::find('1');
    //     $siteSetting->city_id=$request->city_id;
    //     $siteSetting->save();
    //     return redirect()->back();
    // }
}
