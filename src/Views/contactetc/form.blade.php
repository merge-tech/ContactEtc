@extends("layouts.app")
@section("content")

    <div style='max-width:800px;margin:20px auto'>

        @if (config("contactetc.show_errors_above_form",true) && isset($errors) && count($errors))
            <div class="alert alert-danger">
                <b>Sorry, but there was an error:</b>
                <ul class='m-0'>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{--WARNING! THIS ECHOS OUT WHATEVER IS IN THE FORM CONFIG FILE, WITHOUT ESCAPING !--}}
        {!! $contactFormDetails->html_above_form ?? "" !!}

        <form method='post' action='{{$formUrl}}' class='contactetc_form '>

            @csrf

            @foreach($fields as $field)
                <div class='contactetc_field contactetc_error'>
                    @includeFirst([$field->getView(),"contactetc::fields.default"])
                </div>
            @endforeach


            <div class='contactetc_submit'>
                <input type='submit' class='{{$contactFormDetails->submit_button_css_classes ?? 'btn btn-primary'}}'
                       value='{{$contactFormDetails->submit_button_text ?? "Send!"}}'>
            </div>

            <p class='text-muted text-center m-4' style='margin:50px; text-align:center;'>
                <small><a class='text-muted' href='https://webdevetc.com/contactetc'>Laravel Contact Form from
                        WebDevEtc</a>
                </small>
            </p>

        </form>

        {{--WARNING! THIS ECHOS OUT WHATEVER IS IN THE FORM CONFIG FILE, WITHOUT ESCAPING !--}}
        {!! $contactFormDetails->html_below_form ?? "" !!}
    </div>

@endsection