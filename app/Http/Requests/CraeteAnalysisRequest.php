<?php namespace App\Http\Requests;

class CraeteAnalysisRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
        /*gfile
        cpdfile
        geneid
        cpdid
        species
        pathway
        suffix*/

		return [
            'geneid' => 'required',
            'cpdid' => 'required',
            'species' => 'required|min:3',
            'pathway' => 'required|min:3',
            'suffix' => 'required|min:1'
		];
	}


}
