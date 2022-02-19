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



                <button id="addFormBtn" type="button" class="mt-3 btn btn-sm btn-danger">Add New</button>



                    <div class="countdown" >
                        <p id="timer">

                            <span id="timer-mins"></span>
                            <span id="timer-secs"></span>
                            <input type="hidden" id="minutes" value="" />
                            <input type="hidden" id="seconds" value=""/>
{{--                            <input type="text" id="tour_id" value=""/>--}}

                            @if($latest_tour)
                            <input type="hidden" id="end_time" value="{{$latest_tour->end_time}}"/>
                            <input type="hidden" id="tour_id" value="{{$latest_tour->tour_id}}"/>
                            @endif
                        </p>
                    </div>



                <table id="userDataTable" class="table table-striped table-sm table-bordered" cellspacing="0" width="100%">

                    <thead>
                    <tr>
                        <th class="th-sm">Email </th>
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

                <div class="modal-body mx-3">


                    <div class="md-form mb-5">

                        <input   placeholder="Idea" name="idea" type="text" id="idea" class="form-control validate">
                    </div>



                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                        Close
                    </button>
                    <button  id="userAddConfirmBtn" class="btn btn-sm btn-success" onclick="ideaAdd()">Save</button>
                </div>


            </div>
        </div>
    </div>




@endsection

@section('script')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script type="text/javascript">

            var endTime = new Date().getTime() + 15 * 60 * 1000;

            let  t_id = $("#tour_id").val();


                    var endTimerrr=parseFloat($('#end_time').val());




                    var timer = setInterval(function() {

                        let now = new Date().getTime();
                        let t = endTimerrr - now;





                        if (t >= 0) {


                            let mins = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
                            let secs = Math.floor((t % (1000 * 60)) / 1000);



                            $("#minutes").val(("0"+mins).slice(-2));
                            $("#seconds").val(("0"+secs).slice(-2));
                            document.getElementById("timer-mins").innerHTML = ("0"+mins).slice(-2) +
                                "<span class='label'>MIN(S)</span>";

                            document.getElementById("timer-secs").innerHTML = ("0"+secs).slice(-2) +
                                "<span class='label'>SEC(S)</span>";






                        } else {
                            document.getElementById("timer").innerHTML = "The Last Tournament is over!";

                            axios.get('/change_status/'+t_id)
                                .then(function (response) {

                                })
                                .catch(function (error) {

                                })

                        }

                    }, 1000);




                setInterval(function(){
                    let minutes = $("#minutes").val();
                    let  seconds = $("#seconds").val();
                    if(t_id != '') {
                        if (minutes == '10' && seconds == '00') {
                            axios.get("/get_end_time/4/" + t_id)
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
                            axios.get("/get_end_time/2/"+t_id)
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
                            axios.get("/get_end_time/1/"+t_id)
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




            getUsersData();

            function get_alert() {

                axios.get("/ideaCount")
                    .then(function (response) {

                        alert(response.data)

                    })

            }






            function getUsersData() {

                axios.get("/getIdeaData")
                    .then(function(response) {

                        if (response.status == 200) {

                            $('#mainDiv').removeClass('d-none')


                            $('#userDataTable').DataTable().destroy();
                            $('#user_table').empty();
                            var jsonData = response.data;

                            // console.log(jsonData)
                            // $.each(jsonData, function (i, item) {   })
                            for (var i = 0; i < jsonData.length; i++) {

                                // var obj = jsonData[i];
                                 //console.log(obj.id);
                                $('<tr>').html(
                                    "<td>" + jsonData[i].user[0].email + "</td>" +
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

            $('#addFormBtn').click(function () {
                $('#addModal').modal('show');
                $('#idea').val('')
            })








            function ideaAdd() {

                axios.post('/ideaAdd', {
                    idea: $('#idea').val(),
                    time:endTime

                })
                    .then(function (response) {
                        $('#addModal').modal('hide')
                        getUsersData()
                        toastr.success('Idea added')

                        console.log(response.data)

                        if (response.data.count == 8){
                            swal("Tournament Start", "", "success");


                            $("#tour_id").val(response.data.tour_id);
                            $("#end_time").val(response.data.end_time);


                            window.location.reload()
                        }
                    })
                    .catch(function (error) {
                        getUsersData();
                        toastr.error('Something went wrong')
                    });

            }

        </script>

@endsection
