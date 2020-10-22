@extends('app')

@section('content')

    <div class="d-md-flex justify-content-between mb-3">

        <h1><a up-follow href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i>
            {{ trans('messages.my_groups') }}</h1>


        <form class="form-inline" role="search" method="GET" action="{{ route('groups.index.my') }}" up-autosubmit
            up-delay="100" up-target=".groups" up-reveal='false'>
            <div class="input-group">
                <input value="{{ Request::get('search') }}" class="form-control" type="text" name="search"
                    placeholder="{{ __('Filter') }}..." aria-label="Search">

                <div class="input-group-append">
                    <button class="btn btn-secondary" type="submit"><span class="fa fa-search"></span></button>
                </div>
            </div>
        </form>

    </div>



    <div class="groups">
        @if ($groups)
            {!! $groups->appends(request()->query())->links() !!}
            <div class="md:flex content-center flex-wrap">
                @foreach ($groups as $group)
                    <div class="md:flex md:w-1/2 lg:w-1/3">
                        <div class="md:flex-1 rounded shadow m-2">

                            @include('groups.group')
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info" role="alert">
                    {{ trans('messages.nothing_yet') }}
                </div>
        @endif
    </div>



@endsection
