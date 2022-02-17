@extends('layout.app')
@section('content')



    <div id="mainDiv" class="container d-none">

        <div class="row">
            <div class="col-md-12 p-5">

                @if(Session::has('success'))
                    <div class="alert alert-success">
                        {{Session::get('success')}}
                    </div>

                @elseif(Session::has('error'))
                    <div class="alert alert-danger">
                        {{Session::get('error')}}
                    </div>
                @endif


                @if($user_type=='Admin')
                <button id="addFormBtn" type="button" class="mt-3 btn btn-sm btn-danger">Add New</button>

                 @endif



                <table id="userDataTable" class="table table-striped table-sm table-bordered" cellspacing="0" width="100%">

                    <thead>
                    <tr>
                        <th class="th-sm">ID</th>
                        <th class="th-sm">User Name</th>
                        <th class="th-sm">Idea</th>


                    </tr>
                    </thead>
                    <tbody id="user_table">



                    </tbody>
                </table>

            </div>
        </div>
    </div>









    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Add New Idea</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form  action="/ideaAdd" method="POST" class="m-5 loginForm">
                    @csrf
                <div class="modal-body mx-3">


                    <div class="md-form mb-5">

                        <input   placeholder="Idea" name="idea" type="text" id="idea" class="form-control validate">
                    </div>



                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" id="userAddConfirmBtn" class="btn btn-sm btn-success">Save</button>
                </div>

                </form>
            </div>
        </div>
    </div>




@endsection

@section('script')
        <script type="text/javascript">
            getUsersData();


            function getUsersData() {

                axios.get("/getIdeaData")
                    .then(function(response) {

                        if (response.status == 200) {

                            $('#mainDiv').removeClass('d-none')


                            $('#userDataTable').DataTable().destroy();
                            $('#user_table').empty();
                            var jsonData = response.data;

                            console.log(jsonData)
                            // $.each(jsonData, function (i, item) {   })
                            for (var i = 0; i < jsonData.length; i++) {

                                // var obj = jsonData[i];
                                 //console.log(obj.id);
                                $('<tr>').html(
                                    "<td>" + jsonData[i].id + "</td>" +
                                    "<td>" + jsonData[i].user[0].name + "</td>" +
                                    "<td>" + jsonData[i].idea + "</td>"

                                ).appendTo('#user_table');
                            }

                            $('#userDataTable').DataTable({"order":false});
                            $('.dataTables_length').addClass('bs-select');

                        }
                    }).catch(function(error) {


                })

            }

     ;





            $('#addFormBtn').click(function () {
                $('#addModal').modal('show');
            })








        </script>

@endsection
