<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite('resources/css/app.css')
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;200;400;500;700;900&display=swap');
        </style> 
        <title>Epic Movie Quotes</title>
    </head>
    <body stlye="font-family:'inter'; width:full; height:full; ">
        <div style="width: 100%; height: 100%;">
            
            <div style="margin: auto; width:21.4rem;">
                <img src="{{ $message->embed(public_path() . '/assets/mail.png')}}" style="width:21.4rem; height:15rem; margin-top: 1rem;"/>  
            </div>
             
            <div style="margin: auto; width: 21.4rem; margin-top: 2.5rem;">
                <div>
                    <h1 style="margin: auto;  width: 55%; font-size: 1.5rem; font-weight: bold;">{{__('user.reset_password')}}</h1>
                </div>
                <div>
                    <p style="margin: auto; width: 77%; font-size: 1rem; margin-top: 0.5rem;">{{__('user.click_this_button_to_verify_your_email')}}</p>
                </div>
            </div>
            <div style="margin: auto; width:21.4rem; height:4.3rem;">
                <div style="background-color: #22c55e; height: 3.5rem; margin-top: 1.5rem; width:21.4rem; font-size:1rem; border-radius: 1.5rem;">
                        <a href="{{ $url }}" style="display: block; margin: auto; width: 41%; padding-top: 4%; text-decoration: none; font-family:'inter'; color:white; font-weight:bold;" >{{__('user.recover_password')}}</a>
                </div>
            </div>
        </div>
    </body>
</html>


