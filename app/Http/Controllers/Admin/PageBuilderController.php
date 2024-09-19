<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Builder_page;
use App\Models\FileUploader;
use App\Models\FrontendSetting;
use Illuminate\Http\Request;

class PageBuilderController extends Controller
{
    function page_list()
    {
        return view('admin.page_builder.page_list');
    }

    function page_store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required'
        ]);

        Builder_page::insert(['name' => $request->name, 'created_at' => date('Y-m-d H:i:s')]);
        return redirect(route('admin.pages'))->with('success', get_phrase('New home page layout has been added'));
    }

    function page_update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required'
        ]);

        Builder_page::where('id', $id)->update(['name' => $request->name, 'updated_at' => date('Y-m-d H:i:s')]);
        return redirect(route('admin.pages'))->with('success', get_phrase('Home page name has been updated'));
    }

    function page_delete($id)
    {
        Builder_page::where('id', $id)->delete();
        return redirect(route('admin.pages'))->with('success', get_phrase('The page name has been updated'));
    }

    function page_status($id)
    {
        $query = Builder_page::where('id', $id);
        if ($query->first()->status == 1) {
            $query->update(['status' => 0]);
            $response = [
                'success' => get_phrase('Home page deactivated')
            ];
        } else {
            FrontendSetting::where('key', 'home_page')->update(['value' => $query->first()->identifier]);
            $query->update(['status' => 1]);
            $response = [
                'success' => get_phrase('Home page activated')
            ];
        }
        Builder_page::where('id', '!=', $id)->update(['status' => 0]);


        return json_encode($response);
    }

    function page_layout_edit($id)
    {
        return view('admin.page_builder.page_layout_edit', ['id' => $id]);
    }

    function page_layout_update(Request $request, $id)
    {
        $validated = $request->validate([
            'html' => 'required'
        ]);


        //Remove all previous files made by admin
        $files = array_diff(scandir(base_path('/resources/views/components/home_made_by_builder')), array('.', '..'));
        foreach ($files as $file){
            unlink(base_path('/resources/views/components/home_made_by_builder/'.$file));
        }

        
        //Merge developer file with admins changes and then create file for admin
        $elements = $this->find_builder_block_elements($request->html);
        $built_file_names = [];
        foreach($elements as $element){
            $developer_file_content = file_get_contents(base_path("/resources/views/components/home_made_by_developer/".$element['file_name'].'.blade.php'));
            $admin_file_content = $this->replace_builder_content($developer_file_content, $element['content']);
            file_put_contents(base_path("/resources/views/components/home_made_by_builder/".$element['file_name'].'.blade.php'), $admin_file_content);
            $built_file_names[] = $element['file_name'];
        }
        Builder_page::where('id', $id)->update(['html' => json_encode($built_file_names)]);

        return redirect(route('admin.pages'))->with('success', get_phrase('Page layout has been updated'));
    }



    function replace_builder_content($html_1 = "", $html_2 = "")
    {
        //REPLACE $html_1 BY $html_2

        // Extract src and builder-identity attributes from html_2
        preg_match_all('/<img\s+class="builder-editable"\s+builder-identity="(\d+)"\s+src="([^"]+)"/', $html_2, $matches2, PREG_SET_ORDER);

        // Create an associative array to map builder-identity to src
        $srcMap = [];
        foreach ($matches2 as $match) {
            $srcMap[$match[1]] = $match[2];
        }

        // Replace src attributes in html_1 using the srcMap
        $html_1 = preg_replace_callback('/<img\s+class="builder-editable"\s+builder-identity="(\d+)"\s+src="([^"]+)"/', function ($matches) use ($srcMap) {
            $identity = $matches[1];
            if (isset($srcMap[$identity])) {
                return '<img class="builder-editable" builder-identity="' . $identity . '" src="{{asset("' . $srcMap[$identity] . '")}}"';
            }
            return $matches[0];
        }, $html_1);

        // Extract content and builder-identity attributes from html_2 (excluding img tags)
        preg_match_all('/<([^img][^>]*)builder-identity="(\d+)"[^>]*>(.*?)<\/[^>]+>/', $html_2, $matches2, PREG_SET_ORDER);

        // Create an associative array to map builder-identity to content
        $contentMap = [];
        foreach ($matches2 as $match) {
            $contentMap[$match[2]] = $match[3];
        }

        // Replace content in html_1 using the contentMap
        $html_1 = preg_replace_callback('/<([^img][^>]*)builder-identity="(\d+)"[^>]*>(.*?)<\/[^>]+>/', function ($matches) use ($contentMap) {
            $identity = $matches[2];
            if (isset($contentMap[$identity])) {
                return '<' . $matches[1] . 'builder-identity="' . $identity . '">' . $contentMap[$identity] . '<' . substr(strrchr($matches[0], '<'), 1);
            }
            return $matches[0];
        }, $html_1);

        return $html_1;
    }

    function find_builder_block_elements($html)
    {
        // Define a regex pattern to match all divs with builder-block-file-name attribute
        $pattern = '/<div\s+[^>]*builder-block-file-name="([^"]+)"[^>]*>(.*?)<\/div>/s';

        // Use preg_match_all to find all matches
        preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);

        // Collect the file name and HTML content inside each matched element
        $elements = [];
        foreach ($matches as $match) {
            $elements[] = [
                'file_name' => $match[1], // The value of the builder-block-file-name attribute
                'content' => $match[2]    // The inner HTML content of the div
            ];
        }

        return $elements;
    }

    function page_layout_image_update(Request $request)
    {
        $remove_file_arr = explode('/', $request->remove_file);
        $previous_image_path = 'uploads/home-page-builder/' . end($remove_file_arr);
        remove_file($previous_image_path);

        $image_path = FileUploader::upload($request->file, 'uploads/home-page-builder');
        return get_image($image_path);
    }

    function preview($page_id)
    {
        $page_data['page_id'] = $page_id;
        return view('frontend.builder-home.index', $page_data);
    }
}
