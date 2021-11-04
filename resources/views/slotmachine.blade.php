<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Slot Machine</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Styles -->
    <link href="{{asset('css/app.css')}}" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>
<body class="antialiased">
<div class="container">
    <div class="row">
        <h2 class="text-center mb-5">Your Credit:
            <span id="user_credit"> {{session()->get('user_credit')}}</span>
        </h2>
    </div>

    <div class="row justify-content-center">
        <div class="slotwrapper" id="slotwrapper">
            <ul>
                <li data-value="watermelon"><img src="{{asset('img/watermelon.svg')}}"/></li>
                <li data-value="cherry"><img src="{{asset('img/cherry.svg')}}"/></li>
                <li data-value="lemon"><img src="{{asset('img/lemon.svg')}}"/></li>
                <li data-value="orange"><img src="{{asset('img/orange.svg')}}"/></li>
            </ul>
            <ul>
                <li data-value="cherry"><img src="{{asset('img/cherry.svg')}}"/></li>
                <li data-value="watermelon"><img src="{{asset('img/watermelon.svg')}}"/></li>
                <li data-value="orange"><img src="{{asset('img/orange.svg')}}"/></li>
                <li data-value="lemon"><img src="{{asset('img/lemon.svg')}}"/></li>

            </ul>
            <ul>
                <li data-value="lemon"><img src="{{asset('img/lemon.svg')}}"/></li>
                <li data-value="orange"><img src="{{asset('img/orange.svg')}}"/></li>
                <li data-value="watermelon"><img src="{{asset('img/watermelon.svg')}}"/></li>
                <li data-value="cherry"><img src="{{asset('img/cherry.svg')}}"/></li>

            </ul>
        </div>
        <div class="text-center mt-4">
            <button type="button" class="btn btn-success btn-lg" id="btn-start">Start Spin!</button>
            <a class="btn btn-info btn-lg" id="btn-CacheOut">CACHE OUT</a>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="{{asset('/js/slotmachine.js')}}"></script>
<script type="text/javascript">
    $('#btn-start').click(function () {
        $(this).attr('disabled',true)
        $.ajax({
            type: 'GET',
            url: '{{route('slotMachine.startSpin')}}',
            success: function (result){
                if (result.success){
                    startSpin(result.data)
                }
                else {
                    alert('You dont have enough credit')
                }

            },
            error: function (){

            }

        });


    });
    function startSpin(user_credit){
        $('#slotwrapper ul').playSpin({
            time: 1000,
            endNum: [1, 2, 3],
            onFinish: function (num) {

                const firstChar = num.charAt(0)
                const secondChar = num.charAt(1)
                const thirdChar = num.charAt(2)

                const firstSlotValue = $('#slotwrapper ul:first-child li:nth-child(' + firstChar + ')').attr('data-value')
                const secondSlotValue = $('#slotwrapper ul:nth-child(2) li:nth-child(' + secondChar + ')').attr('data-value')
                const thirdSlotValue = $('#slotwrapper ul:last-child li:nth-child(' + thirdChar + ')').attr('data-value')
                // console.log(secondSlotValue)


                $.ajax({
                    url: '{{route('slotMachine.slotResult')}}',
                    data: {
                        first_slot_value: firstSlotValue,
                        second_slot_value: secondSlotValue,
                        third_slot_value: thirdSlotValue,
                    },
                    success: function (result){
                        console.log(result.roll_again_chance)
                        if (!result.success){
                            // alert('again spin')
                            startSpin(user_credit)

                        }
                        else {
                            $('#btn-start').removeAttr('disabled')
                        }

                        updateUserCredit(result.data)

                    }
                })
            }
        });
    }
    function updateUserCredit(credit){
        $('#user_credit').text(credit)
    }
    $(document).ready(function(){
        $('#btn-CacheOut').hover(function (){
            var newq = makeNewPosition();

            if(Math.random() < 0.5) {
                $(this).animate({left: newq[0],top:newq[1]});
            }else if(Math.random() < 0.4){
                $(this).css('cursor','not-allowed')
            }

        },function (){
           //
        })
    });

    function makeNewPosition(){
        var numbers = [0,300,-300]
        var left_rand_number = numbers[Math.floor((Math.random() * numbers.length))]
        var top_rand_number = numbers[Math.floor((Math.random() * numbers.length))]

        return [left_rand_number,top_rand_number];
    }

</script>
</body>
</html>
