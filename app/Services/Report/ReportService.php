<?php

namespace App\Services\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Models\Organization\Organization;
use Carbon\Carbon;
use PdfReport;
use ExcelReport;

class ReportService
{
	private $meta = [];
	private $columns = [];

	public function generateReport(Request $request)
	{
		

	}
}