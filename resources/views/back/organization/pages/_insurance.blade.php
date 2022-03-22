<div class="row">
	<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
		<div class="box box-block tile tile-2 bg-primary mb-2">
			<div class="t-icon right"><i class="fas fa-user-tie"></i></div>
			<div class="t-content">
				<h1 class="mb-1">0</h1>
				<h6 class="text-uppercase">@lang('layout.clients')</h6>
			</div>
		</div>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
		<div class="box box-block tile tile-2 bg-success mb-2">
			<div class="t-icon right"><i class="far fa-money-bill-alt"></i></div>
			<div class="t-content">
				<h1 class="mb-1">{{ number_format($wallet->balance) }}</h1>
				<h6 class="text-uppercase">@lang('layout.balance')</h6>
			</div>
		</div>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
		<div class="box box-block tile tile-2 bg-danger mb-2">
			<div class="t-icon right"><i class="fas fa-database"></i></div>
			<div class="t-content">
				<h1 class="mb-1">0</h1>
				<h6 class="text-uppercase">@lang('layout.products')</h6>
			</div>
		</div>
	</div>
</div>

<div class="box box-block bg-white">
	<div class="clearfix mb-1">
		<h5 class="float-xs-left">@lang('layout.financialanalysis')</h5>
		<div class="float-xs-right">
			<button class="btn btn-link btn-sm text-muted" type="button"><i class="ti-angle-down"></i></button>
			<button class="btn btn-link btn-sm text-muted" type="button"><i class="ti-reload"></i></button>
			<button class="btn btn-link btn-sm text-muted" type="button"><i class="ti-close"></i></button>
		</div>
	</div>
	<div id="multiple" class="chart-container"></div>
</div>