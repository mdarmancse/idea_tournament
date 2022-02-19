@extends('layout.app')
@section('content')

    <div class="container">
        <div class="row">


            @foreach($all_tour as $at )

            <div class="col-md-4 p-3">
                <div class="card">
                    <div class="card-body">
                        <h3 class="count card-title">TOUR-{{$at->tour_id}}</h3>
                        <h3 class="count card-text" id="abx">
                            <span id="timer-mins"></span>
                            <span id="timer-secs"></span>
                        </h3>

                            <input type="hidden" id="minutes_{{$at->tour_id}}" value=""/>
                            <input type="hidden" id="seconds_{{$at->tour_id}}" value=""/>
                            <input type="hidden" id="end_time_{{$at->tour_id}}" value="{{$at->end_time}}"/>
                            <input type="hidden" id="tour_id_{{$at->tour_id}}" value="{{$at->tour_id}}"/>
                        <button id="see_time_{{$at->tour_id}}" onclick="see_time({{$at->tour_id}})" type="button" class="mt-3 btn btn-sm btn-primary">See Remaining Time</button>

                    </div>
                </div>
            </div>
            @endforeach

        </div>

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
                        <th class="th-sm">Name</th>
                        <th class="th-sm">Email</th>
                        <th class="th-sm">User Type</th>


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
                    <h4 class="modal-title w-100 font-weight-bold">Add New User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form  action="/userAdd" method="POST" class="m-5 loginForm">
                    @csrf
                <div class="modal-body mx-3">
                    <div class="md-form mb-5">

                        <input   placeholder="Name" name="name" type="text" id="userNameAddId" class="form-control validate">
                    </div>



                    <div class="md-form mb-5">

                        <input   placeholder="Email" name="email" type="email" id="userEmailAddId" class="form-control validate">
                    </div>

                    <div class="md-form mb-5">

                        <input   placeholder="Enter The Password" name="pass" type="password" id="userPassword" class="form-control validate">
                    </div>

                    <div class="md-form mb-5 mr-5">

                        <select name="user_type" class="browser-default form-control" >
                            <option>User Type</option>
                            <option value="Admin">Admin</option>
                            <option value="User">User</option>
                        </select>
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

            function see_time(sl) {

                // alert(sl)

                var endTimerrr=parseFloat($('#end_time_'+sl).val());
                var tour_id=parseFloat($('#tour_id'+sl).val());

                setInterval(function(){
                    let minutes = $("#minutes_"+sl).val();
                    let  seconds = $("#seconds_"+sl).val();

                    if(tour_id != '') {
                        if (minutes == '10' && seconds == '00') {
                            axios.get("/get_end_time/4/" + tour_id)
                                .then(function (response) {

                                    console.log(response.data)

                                    let data=response.data;

                                    axios.post('/send-mail', {
                                        data: data,
                                        phase: '1st Phase',

                                    }).then(function (response) {



                                    })

                                    swal("1st Phase", "4 winners", "success");
                                })

                        }

                        if (minutes == '05' && seconds == '00') {
                            axios.get("/get_end_time/2/"+tour_id)
                                .then(function (response) {
                                    console.log(response.data)
                                    let data=response.data;

                                    axios.post('/send-mail', {
                                        data: data,
                                        phase: '2nd Phase',

                                    }).then(function (response) {



                                    })
                                    swal("2nd Phase", "2 winners", "success");
                                })

                        }

                        if (minutes == '00' && seconds == '00') {
                            axios.get("/get_end_time/1/"+tour_id)
                                .then(function (response) {
                                    console.log(response.data)
                                    let data=response.data;

                                    axios.post('/send-mail', {
                                        data: data,
                                        phase: 'Final Phase',

                                    }).then(function (response) {



                                    })
                                    swal("Final Phase", "1 winners", "success");
                                })

                        }
                    }
                }, 1000);
                var timer = setInterval(function() {

                    let now = new Date().getTime();
                    let t = endTimerrr - now;





                    if (t >= 0) {


                        let mins = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
                        let secs = Math.floor((t % (1000 * 60)) / 1000);



                        $("#minutes_"+sl).val(("0"+mins).slice(-2));
                        $("#seconds_"+sl).val(("0"+secs).slice(-2));
                        document.getElementById("timer-mins").innerHTML = ("0"+mins).slice(-2) +
                            "<span class='label'>MIN(S)</span>";

                        document.getElementById("timer-secs").innerHTML = ("0"+secs).slice(-2) +
                            "<span class='label'>SEC(S)</span>";






                    } else {
                        document.getElementById("abx").innerHTML = "The  Tournament is over!";

                        axios.get('/change_status/'+tour_id)
                            .then(function (response) {

                            })
                            .catch(function (error) {

                            })

                    }

                }, 1000);


            }



            getUsersData();


            function getUsersData() {

                axios.get("/getUserData")
                    .then(function(response) {

                        if (response.status == 200) {

                            $('#mainDiv').removeClass('d-none')


                            $('#userDataTable').DataTable().destroy();
                            $('#user_table').empty();
                            var jsonData = response.data;
                            // $.each(jsonData, function (i, item) {   })
                            for (var i = 0; i < jsonData.length; i++) {

                                // var obj = jsonData[i];
                                // console.log(obj.id);
                                $('<tr>').html(
                                    "<td>" + jsonData[i].name + "</td>" +
                                    "<td>" + jsonData[i].email + "</td>" +
                                    "<td>" + jsonData[i].user_type + "</td>"

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
