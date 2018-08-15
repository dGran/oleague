@extends('layouts.admin2')

@section('content')

<div class="row no-gutters">
    <div class="col-12 p-0 p-md-4">
        <div class="px-3 py-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0 m-0 mb-1" style="font-size: .9em">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Equipos</li>
                </ol>
            </nav>
            <h5 class="text-uppercase m-0"><strong>Equipos</strong></h5>
        </div>

        <div>

            <div class="col-md-12 notifications">
                @if (session('status'))
                    <div class="alert alert-info autohide">
                        {{ session('status') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger autohide">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            <div class="general-options">

                <form id="frmFilter" role="search" method="get" action="{{ route('admin.teams') }}">
                    <div class="row filters d-none m-0 border-bottom {{ $filterCategory ? 'd-block' : '' }}">
                        <div class="col-12 col-sm-7 col-md-6 col-xl-4 p-3">
                            <h5 class="p-0">Filtros</h5>
                            <select name="filterCategory" id="filterCategory" class="form-control" onchange="setCategoryFilter()">
                                <option value="">Todas las categorías</option>
                                @foreach ($categories as $category)
                                    @if ($category->id == $filterCategory)
                                        <option selected value="{{ $category->id }}">{{ $category->name }}</option>
                                    @else
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row no-gutters p-3">
                        <div class="search col-7 col-md-6 col-xl-4">
                            <div class="input-group search">
                                <input type="text" class="form-control" id="filterName" name="filterName" placeholder="Buscar..." aria-describedby="addon-search" value="{{ $filterName }}" autocomplete="off">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="addon-search">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                            </div> {{-- input-group --}}
                        </div> {{-- search col --}}
                        <div class="actions col-5 col-md-6 col-xl-8 text-right">
                            <div class="btn-group" role="group">
                                <div class="btn-group" role="group">
                                    <button id="btnGroupExcel" type="button" class="btn btn-outline-secondary dropdown-toggle input-group-text" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-file-excel"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupExcel">
                                        <a class="dropdown-item" href="{{ route('admin.teams.export.file',['type'=>'xls', $filterCategory, $filterName]) }}">
                                            <i class="fas fa-file-export fa-fw mr-1 text-secondary"></i>
                                            Exportar (.xls)
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.teams.export.file',['type'=>'xlsx', $filterCategory, $filterName]) }}">
                                            <i class="fas fa-file-export fa-fw mr-1 text-secondary"></i>
                                            Exportar (.xlsx)
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.teams.export.file',['type'=>'csv', $filterCategory, $filterName]) }}">
                                            <i class="fas fa-file-export fa-fw mr-1 text-secondary"></i>
                                            Exportar (.csv)
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-file-import fa-fw mr-1 text-success"></i>
                                            Importar
                                        </a>
                                    </div>
                                </div>
                               <button id="addon-filter" type="button" class="btn btn-outline-secondary input-group-text {{ $filterCategory ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $filterCategory ? 'true' : 'false' }}" autocomplete="off" onclick="showHideFilters()">
                                    <i class="fas fa-filter"></i>
                                </button>
                                <a href="{{ route('admin.teams.add') }}" class="btn btn-primary" id="btnAdd">
                                    <i class="fas fa-plus d-inline-block d-md-none"></i>
                                    <span class="d-none d-md-inline-block">Nuevo equipo</span>
                                </a>
                            </div>
                        </div> {{-- actions col --}}
                    </div> {{-- row --}}
                </form>

            </div> {{-- general-options --}}

            <table class="table table-borderless m-0">

                <thead>
                    <tr class="border-top">
                        <th width="1" scope="col" class="pr-0">
                            <div class="pretty p-icon p-jelly">
                                <input type="checkbox" id="allMark" onchange="showHideAllMultipleOptions()">
                                <div class="state p-primary">
                                    <i class="icon material-icons">done</i>
                                    <label></label>
                                </div>
                            </div>
                        </th>
                        <th width="32" scope="col" colspan="2" class="pl-0">Equipo</th>
                        <th scope="col" class="text-right">Acciones</th>
                    </tr>
                </thead>

                @foreach ($teams as $team)
                    <tr class="border-top animated fadeIn">
                        <td width="1" class="align-middle pr-0">
                            <div class="pretty p-icon p-jelly">
                                    <input type="checkbox" class="mark" value="{{ $team->id }}" name="teamId[]" onchange="showHideMultipleOptions(this)">

                                <div class="state p-primary">
                                    <i class="icon material-icons">done</i>
                                    <label></label>
                                </div>
                            </div>
                        </td>
                        <td width="32" class="align-middle px-0">
                            @if ($team->logo)
                                <img src="{{ @get_headers($team->logo) ? $team->logo : asset('img/teams/broken.png') }}" alt="" width="32">
                            @else
                                <img src="{{ asset('img/no-photo.png') }}" alt="" width="32">
                            @endif
                        </td>
                        <td class="align-middle">
                            <span class="name">{{ $team->name }}</span>
                            <span class="d-block">
                                <small class="text-black-50 text-uppercase">{{ $team->category->name }}</small>
                            </span>
                        </td>
                        <td align="right" class="actions align-middle">

                            <div class="actions">
                                <a id="btnRegActions" class="btn btn-link btn-lg py-1 px-3 dropdown-toggle" data-toggle="dropdown">
                                    <i class="fas fa-ellipsis-h text-secondary"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right my-1" aria-labelledby="btnRegActions">
                                    <a class="dropdown-item text-secondary" href="{{ route('admin.teams.duplicate', $team->id) }}">
                                        <i class="fas fa-clone fa-fw mr-1"></i>
                                        Duplicar
                                    </a>
                                    <a class="dropdown-item text-secondary" href="">
                                        <i class="fas fa-edit fa-fw mr-1"></i>
                                        Editar
                                    </a>
                                    <form id="formDelete{{ $team->id }}" action="{{ route('admin.teams.destroy', $team->id) }}" method="post" class="d-inline">
                                        {{ csrf_field() }}
                                        {{ method_field('delete') }}
                                        <a class="dropdown-item text-danger" onclick="confirmDelete('{{ $team->id }}', '{{ $team->name }}' )" value="Eliminar">
                                            <i class="fas fa-trash fa-fw mr-1"></i>
                                            Eliminar
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div> {{-- card-body --}}

        <div class="clearfix border-top p-3 regs-info">
            @if ($teams->count() > 0)
                <div class="float-left regs-info align-baseline">Registros: {{ $teams->firstItem() }}-{{ $teams->lastItem() }} de {{ $teams->total() }}</div>
                <div class="float-right">{!! $teams->appends(Request::all())->render() !!}</div>
            @else
                <div class="text-center">
                    No se han encontrado registros
                </div>
            @endif
        </div>
    </div> {{-- col --}}
</div> {{-- row --}}

@endsection


@section('right-side')
    <span class="d-block py-3 text-muted" id="multipleinfo">Elementos seleccionados</span>
    <div class="generalOptions animated fadeIn">
        <div class="text-center">
            <a href="{{ route('admin.teams.add') }}" class="btn btn-primary btn-block" id="btnAdd">
                <span>Crear nuevo equipo</span>
            </a>
        </div>
        <ul class="list-group border-top mt-3">
            <li class="list-group-item border-0">
                <a href="">
                    <span class="fas fa-file-export fa-fw mr-1"></span>
                    <span>Exportar (.xls)</span>
                </a>
            </li>
            <li class="list-group-item border-0">
                <a href="">
                    <span class="fas fa-file-export fa-fw mr-1"></span>
                    <span>Exportar (.xlsx)</span>
                </a>
            </li>
            <li class="list-group-item border-0">
                <a href="">
                    <span class="fas fa-file-export fa-fw mr-1"></span>
                    <span>Exportar (.csv)</span>
                </a>
            </li>
            <li class="list-group-item border-0">
                <a href="">
                    <span class="fas fa-file-import fa-fw mr-1"></span>
                    <span>Importar</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="multipleOptions animated fadeIn d-none">
        <div class="text-center">
            <a href="" id="destroyMany" onclick="destroyMany()" class="btn btn-danger btn-block">
                <span>Eliminar</span>
            </a>
        </div>
        <ul class="list-group border-top mt-3">
            <li class="list-group-item border-0">
                <a href="" id="duplicateMany" onclick="duplicateMany()">
                    <span class="fas fa-clone fa-fw mr-1"></span>
                    <span>Duplicar</span>
                </a>
            </li>
            <li class="list-group-item border-0">
                <a href="">
                    <span class="fas fa-file-export fa-fw mr-1"></span>
                    <span>Exportar (.xls)</span>
                </a>
            </li>
            <li class="list-group-item border-0">
                <a href="">
                    <span class="fas fa-file-export fa-fw mr-1"></span>
                    <span>Exportar (.xlsx)</span>
                </a>
            </li>
            <li class="list-group-item border-0">
                <a href="">
                    <span class="fas fa-file-export fa-fw mr-1"></span>
                    <span>Exportar (.csv)</span>
                </a>
            </li>
        </ul>
    </div>

@endsection


@section('js')
<script>
    $(document).ready(function() {

        $("#inpSearch").focus(function(){
            $(this).select();
        });

        Mousetrap.bind(['command+a', 'ctrl+a'], function() {
            var url = $("#btnAdd").attr('href');
            $(location).attr('href', url);
            return false;
        });
        Mousetrap.bind(['command+f', 'ctrl+f'], function() {
            $('#filterName').focus();
            return false;
        });


    });

    function showHideFilters() {
        if ($(".filters").is(':visible')) {
            $(".filters").removeClass('d-block fadeIn');
            $(".filters").addClass('d-none');
        } else {
            $(".filters").removeClass('d-none');
            $(".filters").addClass('d-block fadeIn');
        }
    }

    function setCategoryFilter() {
        $("#frmFilter").submit();
    }

    function duplicateMany() {
        $("#duplicateMany").addClass('disabled');
        var ids = [];
        $(".mark:checked").each(function() {
            ids.push($(this).val());
        });
        var url = '{{ route("admin.teams.duplicate.many", ":ids") }}';
        url = url.replace(':ids', ids);
        window.location.href=url;
    }

    function destroyMany() {
        swal({
            title: "¿Estás seguro?",
            text: 'Se van a eliminar los equipos seleccionados. No se podrán deshacer los cambios!',
            buttons: {
                confirm: {
                    text: "Sí, estoy seguro",
                    value: true,
                    visible: true,
                    className: "btn btn-danger",
                    closeModal: true
                },
                cancel: {
                    text: "No, cancelar",
                    value: null,
                    visible: true,
                    className: "btn btn-secondary",
                    closeModal: true,
                }
            },
            closeOnClickOutside: false,
        })
        .then((value) => {
            $("#destroyMany").addClass('disabled');
            if (value) {
                var ids = [];
                $(".mark:checked").each(function() {
                    ids.push($(this).val());
                });
                var url = '{{ route("admin.teams.destroy.many", ":ids") }}';
                url = url.replace(':ids', ids);
                window.location.href=url;
            }
        });
    }

    function showHideMultipleOptions(element) {

        if ($(element).is(':checked')) {
            $(element).parents('tr').addClass('selected');
        } else {
            $(element).parents('tr').removeClass('selected');
        }

        if ($(".mark:checked").length > 0) {
            if (!$(".multipleOptions").is(':visible')) {
                $(".multipleOptions").removeClass('d-none');
                $(".multipleOptions").addClass('d-block fadeIn');
                $(".generalOptions").removeClass('d-block fadeIn');
                $(".generalOptions").addClass('d-none');
            }
        } else {
            if ($(".multipleOptions").is(':visible')) {
                $(".multipleOptions").removeClass('d-block fadeIn');
                $(".multipleOptions").addClass('d-none');
                $(".generalOptions").removeClass('d-none');
                $(".generalOptions").addClass('d-block fadeIn');
            }
        }
        if ($(".mark:checked").length > 0) {
            $("#multipleinfo").text('Elementos seleccionados: ' + $(".mark:checked").length);
        } else {
            $("#multipleinfo").text('No hay elementos seleccionados');
        }

    }

    function showHideAllMultipleOptions() {
        if ($("#allMark").is(':checked')) {
            $(".mark").prop('checked', true);
            $(".mark").parents('tr').addClass('selected');
        } else {
            $(".mark").prop('checked', false);
            $(".mark").parents('tr').removeClass('selected');
        }

        showHideMultipleOptions();
    }

    function confirmDelete(id, name) {
        swal({
            title: "¿Estás seguro?",
            text: 'Se va a eliminar el equipo "' + name + '". No se podrán deshacer los cambios!',
            buttons: {
                confirm: {
                    text: "Sí, estoy seguro",
                    value: true,
                    visible: true,
                    className: "btn btn-danger",
                    closeModal: true
                },
                cancel: {
                    text: "No, cancelar",
                    value: null,
                    visible: true,
                    className: "btn btn-secondary",
                    closeModal: true,
                }
            },
            closeOnClickOutside: false,
        })
        .then((value) => {
            if (value) {
                $("#formDelete"+id).submit();
            }
        });
    }
</script>
@endsection