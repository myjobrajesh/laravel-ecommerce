<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Models\Attribute;

use Illuminate\Http\Request;


use Validator;

\DB::connection()->enableQueryLog();

class AttributeController extends Controller {

    /**
     * Show all Attributes
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAttributes() {

        $attribute = Attribute::paginate(10);
        
        return view('admin.attribute.show', compact('attribute'));
    }


    /**
     * Return the view for add new Attribute
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addAttribute() {
     
        $attributes = Attribute::groupBy('name')->get();
            
        return view('admin.attribute.add', compact('attributes'));
    }


    /**
     * Add a new Attribute into the Database.
     *
     * @param ProductRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addPostAttribute(Request $request) {
        $name =  $request->input('name');
        $name_select = $request->get("name_select");
        $name = ($name) ? $name : $name_select;
        
        if($value = $request->get('value')) {
            //check if same name and value exists ?
            $exist = Attribute::where(\DB::raw('lower(name)'), strtolower($name))->where(\DB::raw('lower(value)'), strtolower($value))->first();
            if($exist) {
                 \CommonHelper::flash()->error('Error', 'Attribute value exists !');
                 return redirect()->back();
            }
            
            $product = Attribute::insert([
                'name' => strtolower($name),
                'value' => strtolower($value),
                'status' => 'active',
                'created_at'=> date('Y-m-d H:i:s')
            ]);

            // Flash a success message
            \CommonHelper::flash()->success('Success', 'Attribute created successfully!');
       // }
        } else {
            \CommonHelper::flash()->error('Error', 'Attribute value required!');
            return redirect()->back();
        }

        // Redirect back to Show all products page.
        return redirect()->route('admin.shop.attribute.show');
    }



    /**
     * Return the view to edit & Update the Attribute
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editAttribute($id) {

        // Find the product ID
        $attribute = Attribute::where('id', '=', $id)->find($id);

        // If no product exists with that particular ID, then redirect back to Show Products Page.
        if (!$attribute) {
            return redirect('admincare/shop/attributes');
        }

        // Return view with products and categories
        return view('admin.attribute.edit', compact('attribute'));

    }


    /**
     * Update a Attribute
     *
     * @param $id
     * @param ProductEditRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAttribute($id, Request $request) {

        // Find the Products ID from URL in route
        $product = Attribute::findOrFail($id);
        //check name and value required
        $response = array();
            $validator = Validator::make($request->all(), array(
                    'name' => 'required',
                    'value' => 'required'
                    )
            );
            if ($validator->fails()) {
                foreach($validator->errors()->getMessages() as $msg) {
                    $msgArr[] = $msg[0];
                }
                \CommonHelper::flash()->error('Error', implode("<br>", $msgArr));
                 return redirect()->back();
            }
            $name = strtolower($request->get('name'));
            $value = strtolower($request->get('value'));
            //check another attribute exists
            $exist = Attribute::where(\DB::raw('lower(name)'), $name)
                    ->where(\DB::raw('lower(value)'), $value)
                    ->where('id', '!=', $id)->first();
            if($exist) {
                 \CommonHelper::flash()->error('Error', 'Attribute value exists !');
                 return redirect()->back();
            }
            // Update product
            $product->name = $name;
            $product->value = $value;
            $product->status = $request->input('status');
            $product->updated_at = date('Y-m-d H:i:s');
            $product->save();

            // Flash a success message
            \CommonHelper::flash()->success('Success', 'Attribute updated successfully!');

        // Redirect back to Show all categories page.
        return redirect()->route('admin.shop.attribute.show');
    }

    /**
     * Delete Attribute
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAttribute($id) {

        Attribute::findOrFail($id)->delete();
        // Then redirect back.
        return redirect()->back();
    }



}