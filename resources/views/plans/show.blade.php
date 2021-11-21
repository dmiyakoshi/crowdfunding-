<x-app-layout>
    <div class="container lg:w-3/4 md:w-4/5 w-11/12 mx-auto my-8 px-4 py-4 bg-white shadow-md">
        {{-- {{ dd($plan,$plan->method()) }} --}}
        <x-flash-message :message="session('notice')" />
        <x-validation-errors :errors="$errors" />

        <article class="mb-2">
            <div class="flex justify-between text-sm">
                <div class="flex item-center">
                    <div class="border border-gray-900 px-2 h-7 leading-7 rounded-full">{{ $plan->method->name }}</div>
                </div>
                <div>
                    <span>作成日 {{ $plan->created_at->format('Y-m-d') }}</span>
                </div>
            </div>
            <p class="text-gray-700 text-base text-right">募集期限 :{{ $plan->due_date }}</p>
            <h2 class="font-bold font-sans break-normal text-gray-900 pt-6 pb-1 text-3xl md:text-4xl">
                {{ $plan->title }}</h2>
            <div class="flex mt-1 mb-3">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div><img src="{{ $plan->user->profile_photo_url }}" alt=""
                            class="h-10 w-10 rounded-full object-cover mr-3"></div>
                @endif
                <h3 class="text-lg h-10 leading-10">{{ $plan->user->name }}</h3>
            </div>
            {{-- {{ dd($plan->imageUrls) }} --}}
            <div>
                <hr class="grid grid-cols-1 divide-y divide-blue-700 my-6">
                @isset($plan->imageUrls[0])
                    <img src="{{ $plan->imageUrls[0] }}" alt="image" class="object-contain">
                @endisset
                <p class="text-gray-700 text-base">{!! nl2br(e($plan->introduction)) !!}</p>
            </div>
            <div>
                <hr class="grid grid-cols-1 divide-y divide-blue-700 my-6">
                <p class="text-gray-700 text-xl">{!! nl2br(e($plan->heading_do)) !!}</p>
                <hr class="grid grid-cols-1 divide-y divide-blue-300">
                @isset($plan->imageUrls[1])
                    <img src="{{ $plan->imageUrls[1] }}" alt="image" class="object-contain">
                @endisset
                <p class="text-gray-700 text-base">{!! nl2br(e($plan->description_do)) !!}</p>
            </div>
            <div>
                <hr class="grid grid-cols-1 divide-y divide-blue-700 my-6">
                <p class="text-gray-700 text-xl">{!! nl2br(e($plan->heading_reason)) !!}</p>
                <hr class="grid grid-cols-1 divide-y divide-blue-300">
                @isset($plan->imageUrls[2])
                    <img src="{{ $plan->imageUrls[2] }}" alt="image" class="object-contain">
                @endisset
                <p class="text-gray-700 text-base">{!! nl2br(e($plan->description_reason)) !!}</p>
            </div>
            <div>
                <hr class="grid grid-cols-1 divide-y divide-blue-700 my-6">
                <p class="text-gray-700 text-xl">お金の使い方</p>
                <hr class="grid grid-cols-1 divide-y divide-blue-300">
                @isset($plan->imageUrls[3])
                    <img src="{{ $plan->imageUrls[3] }}" alt="image" class="object-contain">
                @endisset
                <p class="text-gray-700 text-base">{!! nl2br(e($plan->how_use_money)) !!}</p>
            </div>
        </article>

        <div class="flex flex-col sm:flex-row items-center sm:justify-end text-center my-4">
            @if (Auth::guard(\App\Consts\UserConst::GUARD)->check())
                <a href="{{ route('plans.gifts.create', $plan) }}"
                    class="bg-gradient-to-r from-indigo-500 to-blue-600 hover:bg-gradient-to-l hover:from-blue-500 hover:to-indigo-600 text-gray-100 p-2 rounded-full tracking-wide font-semibold shadow-lg cursor-pointer transition ease-in duration-500 w-full sm:w-32 sm:mr-2 mb-2 sm:mb-0">リターン作成</a>
            @endif
            @if (Auth::guard(\App\Consts\UserConst::GUARD)->check() &&
    Auth::guard(\App\Consts\UserConst::GUARD)->user()->can('update', $plan))
                <a href="{{ route('plans.edit', $plan) }}"
                    class="bg-gradient-to-r from-indigo-500 to-blue-600 hover:bg-gradient-to-l hover:from-blue-500 hover:to-indigo-600 text-gray-100 p-2 rounded-full tracking-wide font-semibold shadow-lg cursor-pointer transition ease-in duration-500 w-full sm:w-32 sm:mr-2 mb-2 sm:mb-0">編集</a>
            @endif
            @if (Auth::guard(\App\Consts\UserConst::GUARD)->check() &&
    Auth::guard(\App\Consts\UserConst::GUARD)->user()->can('delete', $plan))
                <form action="{{ route('plans.destroy', $plan) }}" method="post" class="w-full sm:w-32">
                    @csrf
                    @method('DELETE')
                    <input type="submit" value="削除" onclick="if(!confirm('削除しますか？')){return false};"
                        class="bg-gradient-to-r from-pink-500 to-purple-600 hover:bg-gradient-to-l hover:from-purple-500 hover:to-pink-600 text-gray-100 p-2 rounded-full tracking-wide font-semibold shadow-lg cursor-pointer transition ease-in duration-500 w-full sm:w-32">
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
