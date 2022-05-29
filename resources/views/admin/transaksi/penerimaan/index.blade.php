@extends('admin.layout')

@section('css')
@endsection

@section('content')
    @if (\Illuminate\Support\Facades\Session::has('success'))
        <script>
            Swal.fire("Berhasil!", '{{\Illuminate\Support\Facades\Session::get('success')}}', "success")
        </script>
    @endif
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Halaman Penerimaan Service</h4>
        <ol class="breadcrumb breadcrumb-transparent mb-0">
            <li class="breadcrumb-item">
                <a href="/dashboard">Dashboard</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Penerimaan Service
            </li>
        </ol>
    </div>
    <div class="w-100 p-2 mt-2">
        <div class="text-right mb-2 pr-3">
            <a href="/penerimaan/tambah" class="btn btn-primary"><i class="fa fa-plus mr-1"></i><span
                    class="font-weight-bold">Tambah</span></a>
        </div>
        <table id="table-data" class="display w-100 table table-bordered">
            <thead>
            <tr>
                <th width="5%" class="text-center">#</th>
                <th width="7%">No. Penerimaan</th>
                <th width="12%" class="text-center">Tanggal</th>
                <th>Customer</th>
                <th width="15%" class="text-center">Nama Motor</th>
                <th width="12%" class="text-center">Plat Nomor</th>
                <th class="text-center">Status</th>
                <th width="15%" class="text-center">Action</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

@endsection

@section('js')
    <script src="{{ asset('/js/helper.js') }}"></script>
    <script type="text/javascript">
        var table;
        function destroy(id) {
            AjaxPost('/member/delete', {id}, function () {
                window.location.reload();
            });
        }

        function childElement(d) {
            // `d` is the original data object for the row
            return ('<div></div>');
        }

        $(document).ready(function () {
            table = DataTableGenerator('#table-data', '/penerimaan/data', [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
                {data: 'no_penerimaan'},
                {data: 'tanggal'},
                {data: 'user.member.nama'},
                {data: 'nama_barang'},
                {data: 'plat'},
                {data: 'status'},
                {
                    data: null, render: function (data, type, row, meta) {
                        return '<a href="/member/edit/'+data['id']+'" class="btn btn-sm btn-warning btn-edit" data-id="'+data['id']+'"><i class="fa fa-edit"></i></a>' +
                            '<a href="#" class="btn btn-sm btn-danger btn-delete" data-id=""><i class="fa fa-trash"></i></a>' +
                            '<a href="#" class="btn btn-sm btn-info btn-delete dt-control" data-id=""><i class="fa fa-level-down"></i></a>';
                    }
                },
            ]);

            $('#table-data tbody').on('click', 'a.dt-control', function (e) {
                e.preventDefault();
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child(childElement(row.data())).show();
                    tr.addClass('shown');
                }
            });
            $('.btn-delete').on('click', function (e) {
                e.preventDefault();
                let id = this.dataset.id;
                AlertConfirm('Apakah anda yakin menghapus?', 'Data yang dihapus tidak dapat dikembalikan!', function () {
                    destroy(id);
                })
            });
        });
    </script>
@endsection
