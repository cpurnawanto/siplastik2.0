<?php

namespace Config;

class Validation
{
	//--------------------------------------------------------------------
	// Setup
	//--------------------------------------------------------------------

	/**
	 * Stores the classes that contain the
	 * rules that are available.
	 *
	 * @var array
	 */
	public $ruleSets = [
		\CodeIgniter\Validation\Rules::class,
		\CodeIgniter\Validation\FormatRules::class,
		\CodeIgniter\Validation\FileRules::class,
		\CodeIgniter\Validation\CreditCardRules::class,
		\App\Libraries\Validations\DateRules::class
	];

	/**
	 * Specifies the views that are used to display the
	 * errors.
	 *
	 * @var array
	 */
	public $templates = [
		'list'   => 'CodeIgniter\Validation\Views\list',
		'single' => 'CodeIgniter\Validation\Views\single',
	];

	//--------------------------------------------------------------------
	// Rules
	//--------------------------------------------------------------------

	/**
	 * Validasi untuk import file excel
	 */
	public $excel_import = [
		'excel_import' => 'uploaded[excel_import]|max_size[excel_import,5120]|ext_in[excel_import,xls]'
	];

	public $excel_import_errors = [
		'excel_import' => [
			'ext_in'    => 'File excel hanya boleh diisi dengan xls (sesuai template)',
			'max_size'  => 'File excel maksimal 5MB',
			'uploaded'  => 'File excel wajib diisi'
		]
	];
}
