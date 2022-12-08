<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite('resources/css/app.css')
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;200;400;500;700;900&display=swap');
        </style> 
        <title>Coronatime</title>
    </head>
    <body class="flex flex-col items-center font-['inter'] w-full h-full">

            <div class="w-[21.4rem] lg:w-[24.5rem]">
                <div class="flex lg:justify-center mt-6 w-full">
                    <img src="/assets/corona.png"/>
                </div>

                <div class="h-96">
                    <div class="flex justify-center mt-11 lg:mt-28">
                        <h2 class="font-black text-xl lg:text-2xl">{{__('user.reset_password')}}</h2>
                    </div>
                    
                    <form method="POST" action="{{route('password.email')}}" class="mt-10 lg:mt-14">
                        @csrf

                        <div class="flex flex-col w-full text-sm lg:text-base hidden"> 
                            <label for="email" class="font-bold">{{__('user.email')}}</label>
                            <input name="email" type="email" placeholder="Enter email" value="{{request('email')}}" 
                            class="focus:border-[#2029f3] outline-0 rounded-lg border-2 mt-2 pl-6 h-14">
                        </div>

                        <div class="flex flex-col w-full text-sm lg:text-base"> 
                            <label for="password" class="font-bold">{{__('user.new_password')}}</label>
                            <input name="password" type="password" placeholder="{{__('user.enter_new_password')}}" 
                            class="focus:border-[#2029f3] outline-0 rounded-lg border-2 mt-2 pl-6 h-14">
                        </div>
                        @error('password')
                        <span class="flex text-red-600 ml-1 mt-1">
                            <img src="/assets/error.svg" />
                            <p class="ml-1">{{ $message }}</p>
                        </span>
                        @enderror
                        <div class="flex flex-col w-full mt-4 text-sm lg:text-base"> 
                            <label for="password_confirmation" class="font-bold">{{__('user.repeat_password')}}</label>
                            <input name="password_confirmation" type="password" placeholder="{{__('user.repeat_password')}}" 
                            class="focus:border-[#2029f3] outline-0 rounded-lg border-2 mt-2 pl-6 h-14">
                        </div>
                        <div class="flex flex-col w-full text-sm lg:text-base hidden"> 
                            <label for="token" class="font-bold"></label>
                            <input name="token" value="{{$token}}" placeholder="{{__('user.repeat_new_password')}}" class="border-2 mt-2 pl-6 h-14">
                        </div>

                        <div class="h-14 mt-80 lg:mt-14 flex justify-center items-center font-black text-white text-base bg-green-500 rounded">
                            <button class="px-32 lg:px-40 py-4" type="submit">{{__('user.save_changes')}}</button>
                        </div>
                    </form>
                </div>
            </div>
    </body>
</html>
