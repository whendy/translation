<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Translation Language Manager">
    <meta name="author" content="Whendy, Ahmad Windi Wijayanto">
    <title>Translation Language Manager</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</head>
<body>

<div class="container py-4">
    <header class="pb-3 mb-4 border-bottom">
        <a href="{{ route('whendy.translation.index') }}" class="d-flex align-items-center text-dark text-decoration-none">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="48px" height="48px" viewBox="0,0,256,256"><defs><linearGradient x1="25.447" y1="4.921" x2="44.728" y2="36.29" gradientUnits="userSpaceOnUse" id="color-1"><stop offset="0" stop-color="#bdc8d1"></stop><stop offset="1" stop-color="#152027"></stop></linearGradient><linearGradient x1="3.865" y1="9.86" x2="23.412" y2="41.663" gradientUnits="userSpaceOnUse" id="color-2"><stop offset="0" stop-color="#484b4f"></stop><stop offset="1" stop-color="#a2a8b1"></stop></linearGradient><linearGradient x1="29.064" y1="23.554" x2="38.79" y2="23.554" gradientUnits="userSpaceOnUse" id="color-3"><stop offset="0" stop-color="#f5f6f7"></stop><stop offset="1" stop-color="#f2f3f5"></stop></linearGradient><linearGradient x1="28" y1="23.5" x2="40" y2="23.5" gradientUnits="userSpaceOnUse" id="color-4"><stop offset="0" stop-color="#f5f6f7"></stop><stop offset="1" stop-color="#f2f3f5"></stop></linearGradient><linearGradient x1="33" y1="18" x2="35" y2="18" gradientUnits="userSpaceOnUse" id="color-5"><stop offset="0" stop-color="#f5f6f7"></stop><stop offset="1" stop-color="#f2f3f5"></stop></linearGradient></defs><g transform=""><g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.33333,5.33333)"><path d="M42,8h-23l11,30h12c1.105,0 2,-0.895 2,-2v-26c0,-1.105 -0.895,-2 -2,-2z" fill="url(#color-1)"></path><path d="M19,8h-13c-1.105,0 -2,0.895 -2,2v26c0,1.105 0.895,2 2,2h8v7.998c0,0.891 1.077,1.337 1.707,0.707l8.705,-8.705h5.588z" fill="url(#color-2)"></path><path d="M12,25h6v2h-6z" fill="#ffffff"></path><path d="M12.109,29l2.891,-9l2.906,9h2.11l-4.016,-12h-2l-4,12z" fill="#ffffff"></path><path d="M29.064,27.223c0.061,-0.031 4.994,-3.219 7.936,-9.115l1.79,0.892c-3.082,6.25 -7.457,9.292 -8.509,10z" fill="url(#color-3)"></path><path d="M38,29c0,0 -5,-2.583 -7.769,-6.998l1.769,-1.002c2.333,3.833 6.981,6.26 6.981,6.26zM28,18h12v2h-12z" fill="url(#color-4)"></path><path d="M33,16h2v4h-2z" fill="url(#color-5)"></path></g></g></g></svg>
            <span class="fs-4">Translation Language Manager</span>
        </a>
    </header>

    <div class="row align-items-md-stretch mb-4">
        <div class="col-md-7">
            <h5>@lang('Language') <button type="button" class="btn btn-sm btn-primary show_modal_sm" data-action="{{ route('whendy.translation.language.add_edit') }}" data-method="GET" title="@lang('Add Language')"><i class="bi bi-plus-circle"></i> @lang('Add Language')</button> </h5>
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th style="width: 2%;">@lang('Locale')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Default')</th>
                            <th style="width: 10%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($languages))
                            @foreach($languages as $language)
                                <tr>
                                    <td>{{ $language->locale }}</td>
                                    <td>{{ $language->name }}</td>
                                    <td>{{ ((int)$language->status == 1 ? 'Acitve': 'Not Active') }}</td>
                                    <td>@if((int)$language->default == 1)<strong class="text-success" title="Default"><i class="bi bi-check-square-fill"></i></strong>@else @if((int)$language->status == 1)<a href="javascript:void(0);" class="text-secondary makeItDefault" data-action="{{ route('whendy.translation.language.save', ['id' => $language->id, 'set-default' => true]) }}" title="Make it Default"><strong><i class="bi bi-square"></i></strong></a> @endif @endif</td>
                                    <td>
                                        <a href="javascript:void(0);" class="text-warning show_modal_sm" data-action="{{ route('whendy.translation.language.add_edit', $language->id) }}" data-method="GET" title="@lang('Edit Language')"><i class="bi bi-pencil-square"></i> Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center"><i>@lang('Data Empty')</i></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h5>@lang('Translation') @if($language_active)<button type="button" class="btn btn-sm btn-primary show_modal_xl" data-action="{{ route('whendy.translation.translation.add_edit') }}" data-method="GET" title="@lang('Add Translation')"><i class="bi bi-plus-circle"></i> @lang('Add Translation')</button> @endif</h5>
            <div class="row row-cols-auto">
                <div class="col">
                    <form method="GET">
                        <div class="input-group mb-3">
                            <input type="text" name="search" class="form-control" value="{{ Input::get('search') }}" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
                            <button class="btn btn-outline-secondary" type="submit" id="button-addon2"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead>
                    <tr>
                        <th>@lang('Group')</th>
                        <th>@lang('Key Usage')</th>
                        <th>@lang('Translation')</th>
                        <th style="width: 8%;"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($translations))
                        @foreach($translations as $translation)
                            <tr>
                                <td>{{ $translation->group }}</td>
                                <td>{{ ($translation->group == '*' ? str_replace('*.', '', $translation->code) : $translation->code) }}</td>
                                <td>
                                    <ul class="list-unstyled">
                                        @foreach ($languages as $language)
                                            <li class="item pt-1 pb-1 border-bottom">
                                                <div class="product-info m-l-40">
                                                    <strong class="product-title"><u>{{ $language->name . ' [' . $language->locale . ']' }}</u></strong>
                                                    <br/>
                                                    <span class="text-decoration-dashed product-description">
                                                        @if(!empty($translation->{"language_$language->locale"})) {{ $translation->{"language_$language->locale"} }} @else <i class="text-danger">@lang('Not translated')</i> @endif
                                                    </span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <a href="javascript:void(0);" class="text-warning show_modal_xl" data-action="{{ route('whendy.translation.translation.add_edit', $translation->code) }}" data-method="GET" title="@lang('Edit Translation')"><i class="bi bi-pencil-square"></i> Edit</a>
                                    <br/>
                                    <a href="javascript:void(0);" class="text-danger deleteTranslation" data-action="{{ route('whendy.translation.translation.delete') }}" data-value='{{ json_encode(['code' => $translation->code]) }}' title="@lang('Delete Translation'): {{ ($translation->group == '*' ? str_replace('*.', '', $translation->code) : $translation->code) }}"><i class="bi bi-trash"></i> Delete</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="text-center"><i>@lang('Data Empty')</i></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-12">
            <nav class="justify-content-end" aria-label="Page navigation">
                <?php echo $translations->appends($appends_link)->links(); ?>
            </nav>
        </div>
    </div>

</div>

<div class="modal fade modal-sm" id="modal-sm" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-sm-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-sm-label"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body-load" id="modal-sm-body-load">

            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-xl" id="modal-xl" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-xl-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-xl-label"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body-load" id="modal-xl-body-load">

            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
@include("whendy/translation::js")
</body>
</html>
