<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Models\Warning;
use App\Models\Unit;

class WarningController extends Controller
{
    /*
        INÍCIO - LIVRO DE OCORRÊNCIAS (getMyWarnings)
    */
    public function getMyWarnings(Request $request) {
        $arrays = ['errors' => ''];

        $property = $request->input('property');

        if ($property) {
            $user = auth()->user();

            $unit = Unit::where('id', $property)
            ->where('id_owner', $user['id'])
            ->count();

            if ($unit > 0) {
                $warnings = Warning::where('id_unit', $property)
                ->orderBy('datecreated', 'DESC')
                ->orderBy('id', 'DESC')
                ->get();

                foreach ($warnings as $warnKey => $warnValue) {
                    $warnings[$warnKey]['datecreated'] = date('d/m/Y', strtotime($warnValue['datecreated']));
                    $photoList = [];

                    $photos = explode(',', $warnValue['photos']);
                    foreach ($photos as $photo) {
                        if (!empty($photo)) {
                            $photoList[] = asset('storage/'.$photo);
                        }
                    }
                    $warnings[$warnKey]['photos'] = $photoList;
                }
                $array['list'] = $warnings;
            } else {
                $array['error'] = 'Esta unidade não é sua.';
            }
        } else {
            $array['error'] = 'A propriedade é necessária';
        }
        return $array;
    }
    /*
        FIM - LIVRO DE OCORRÊNCIAS (getMyWarnings)
    */

    /*
        INÍCIO - LIVRO DE OCORRÊNCIAS (addWarningFile)
    */
    public function addWarningFile(Request $request) {
        $array = ['error' => ''];
        
        $validator = Validator::make($request->all(), [
            'photo' => 'required|file|mimes:jpg,png'
        ]);
        
        if (!$validator->fails()) {
            $file = $request->file('photo')->store('public');

            $array['photo'] = asset(Storage::url($file));
        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }
        return $array;
    }
    /*
        FIM - LIVRO DE OCORRÊNCIAS (addWarngingFile)
    */

    /*
        INÍCIO - LIVRO DE OCORRÊNCIAS (setWarning)
    */
    public function setWarning(Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'property' => 'required'
        ]);

        if (!$validator->fails()) {
            $title = $request->input('title');
            $property = $request->input('property');
            $list = $request->input('list');

            $newWarn = new Warning();
            $newWarn->id_unit = $property;
            $newWarn->title = $title;
            $newWarn->status = 'IN_REVIEW';
            $newWarn->datecreated = date('Y-m-d');

            if ($list && is_array($list)) {
                $photos = [];

                foreach ($list as $listItem) {
                    $url = explode('/', $listItem);
                    $photos[] = end($url);
                }
                $newWarn->photos = implode(',', $photos);
            } else {
                $newWarn->photos = '';
            }
            $newWarn->save();
        } else {
            $array['error'] = $validator->errors()->first();
            return $array;
        }
        return $array;
    }
    /*
        FIM - LIVRO DE OCORRÊNCIAS (setWarning)
    */
}
