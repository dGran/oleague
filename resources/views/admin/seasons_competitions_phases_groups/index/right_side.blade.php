<div class="tableOptions animated fadeIn">
    <a href="{{ route('admin.season_competitions_phases_groups.add', [$phase->competition->slug, $phase->slug]) }}" class="btn btn-primary {{ $phase->groups_available_participants() == 0 ? 'disabled' : '' }}" id="btnAdd">
        <i class="fas fa-plus mr-2"></i><span>Nuevo</span>
    </a>

    <ul class="list-group border-top mt-3">
        <li class="list-group-item border-0 px-0">
            <a href="" onclick="import_file()">
               <form
                id="frmImport"
                    lang="{{ app()->getLocale() }}"
                    role="form"
                    method="POST"
                    action="{{ route('admin.season_competitions_phases_groups.import.file', [$phase->competition->slug, $phase->slug]) }}"
                    enctype="multipart/form-data"
                    data-toggle="validator"
                    autocomplete="off">
                    {{ csrf_field() }}
                    <input type="file" name="import_file" id="import_file" class="d-none">
                    <span class="fas fa-file-import fa-fw mr-1"></span>
                    <span>Importar</span>
                </form>
            </a>
        </li>
        @if ($groups->count()>0)
            <li class="list-group-item border-0 px-0">
                <a href="" onclick="export_file('xls')">
                    <span class="fas fa-file-export fa-fw mr-1"></span>
                    <span>Exportar (.xls)</span>
                </a>
            </li>
            <li class="list-group-item border-0 px-0">
                <a href="" onclick="export_file('xlsx')">
                    <span class="fas fa-file-export fa-fw mr-1"></span>
                    <span>Exportar (.xlsx)</span>
                </a>
            </li>
            <li class="list-group-item border-0 px-0">
                <a href="" onclick="export_file('csv')">
                    <span class="fas fa-file-export fa-fw mr-1"></span>
                    <span>Exportar (.csv)</span>
                </a>
            </li>
        @endif
    </ul>
</div>

<div class="rowOptions animated fadeIn d-none">
    <a href="" class="btn btn-danger" onclick="destroyMany()">
        <i class="fas fa-trash mr-2"></i>Eliminar
    </a>
    <ul class="list-group border-top mt-3">
        <li class="rowOptions-Participants list-group-item border-0 px-0 d-none">
            <a href="" onclick="participants(this)">
                <span class="fas fa-users-cog fa-fw mr-1"></span>
                <span>Participantes</span>
            </a>
        </li>
        <li class="rowOptions-Competition list-group-item border-0 px-0 d-none">
            <a href="" onclick="competition(this)">
                <span class="fas fa-futbol fa-fw mr-1"></span>
                <span>Competición</span>
            </a>
        </li>
        <li class="rowOptions-Edit list-group-item border-0 px-0 d-none">
            <a href="" onclick="edit(this)">
                <span class="fas fa-edit fa-fw mr-1"></span>
                <span>Editar</span>
            </a>
        </li>
        <li class="list-group-item border-0 px-0">
            <a href="" onclick="export_file_selected('xls')">
                <span class="fas fa-file-export fa-fw mr-1"></span>
                <span>Exportar (.xls)</span>
            </a>
        </li>
        <li class="list-group-item border-0 px-0">
            <a href="" onclick="export_file_selected('xlsx')">
                <span class="fas fa-file-export fa-fw mr-1"></span>
                <span>Exportar (.xlsx)</span>
            </a>
        </li>
        <li class="list-group-item border-0 px-0">
            <a href="" onclick="export_file_selected('csv')">
                <span class="fas fa-file-export fa-fw mr-1"></span>
                <span>Exportar (.csv)</span>
            </a>
        </li>
    </ul>
</div>