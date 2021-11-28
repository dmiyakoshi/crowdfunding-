{{-- {{ dd($plan->releseFlag , (($plan->total / $plan->goal) >= 0.1), $plan->total, $plan->startFlag) }} --}}
<x-app-layout>
    <div class="container lg:w-3/4 md:w-4/5 w-11/12 mx-auto my-8 px-4 py-4 bg-white shadow-md">
        <x-flash-message :message="session('notice')" />
        <x-validation-errors :errors="$errors" />

        <article class="mb-2">
            <div class="flex justify-between text-sm">
                <div class="flex item-center">
                    <div class="border border-gray-900 px-2 h-7 leading-7 rounded-full">{{ $plan->method->name }}</div>
                </div>
                <div class="flex item-center">
                    <div class="border border-gray-900 px-2 h-7 leading-7 rounded-full">{{ $plan->status }}</div>
                </div>
                <div>
                    <span>作成日 {{ $plan->created_at->format('Y-m-d') }}</span>
                </div>
                {{-- 確認 後で消す --}}
                {{-- <div>
                    <span>リリース前かの確認 {{ $plan->releseFlag }}</span>
                </div>
                <div>
                    <span>募集開始できるかのフラグ {{ $plan->startFlag }}</span>
                </div> --}}
                {{-- 確認終了 --}}
            </div>
            <p class="text-gray-700 text-base text-right">募集開始日 :{{ $plan->relese_date }}</p>
            <p class="text-gray-700 text-base text-right">募集期限 :{{ $plan->due_date }}</p>
            <h2 class="font-bold font-sans break-normal text-gray-900 pt-6 pb-1 text-3xl md:text-4xl">
                {{ $plan->title }}</h2>
            <h6 class="font-sans break-normal text-gray-900 pt-6 pb-1 text-3xl md:text-4xl">
                目標金額: {{ $plan->goal }}円</h6>
            <h6 class="font-sans break-normal text-gray-900 pt-6 pb-1 text-3xl md:text-4xl">
                支援総額: {{ $plan->total }}円 達成率: {{ ($plan->total * 100) / $plan->goal }}%</h6>
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
                <p class="text-gray-700 text-xl mt-8">{!! nl2br(e($plan->heading_do)) !!}</p>
                <hr class="grid grid-cols-1 divide-y divide-blue-300">
                @isset($plan->imageUrls[1])
                    <img src="{{ $plan->imageUrls[1] }}" alt="image" class="object-contain">
                @endisset
                <p class="text-gray-700 text-base">{!! nl2br(e($plan->description_do)) !!}</p>
            </div>
            <div>
                <p class="text-gray-700 text-xl mt-8">{!! nl2br(e($plan->heading_reason)) !!}</p>
                <hr class="grid grid-cols-1 divide-y divide-blue-300">
                @isset($plan->imageUrls[2])
                    <img src="{{ $plan->imageUrls[2] }}" alt="image" class="object-contain">
                @endisset
                <p class="text-gray-700 text-base">{!! nl2br(e($plan->description_reason)) !!}</p>
            </div>
            <div>
                <p class="text-gray-700 text-xl mt-8">お金の使い方</p>
                <hr class="grid grid-cols-1 divide-y divide-blue-300">
                @isset($plan->imageUrls[3])
                    <img src="{{ $plan->imageUrls[3] }}" alt="image" class="object-contain">
                @endisset
                <p class="text-gray-700 text-base">{!! nl2br(e($plan->how_use_money)) !!}</p>
            </div>
        </article>
        {{-- プロジェクト変更、編集など --}}
        <div class="flex flex-col sm:flex-row items-center sm:justify-end text-center my-4">
            {{-- 公開に変更 --}}
            @if (Auth::guard(\App\Consts\UserConst::GUARD)->check() &&
    Auth::guard(\App\Consts\UserConst::GUARD)->user()->can('update', $plan) &&
    $plan->public == 0)
                <a href="{{ route('plans.change.public', $plan) }}"
                    onclick="if(!confirm('公開してよろしいですか?')){return false};"
                    class="bg-gradient-to-r from-indigo-500 to-blue-600 hover:bg-gradient-to-l hover:from-blue-500 hover:to-indigo-600 text-gray-100 p-2 rounded-full tracking-wide font-semibold shadow-lg cursor-pointer transition ease-in duration-500 w-full sm:w-32 sm:mr-2 mb-2 sm:mb-0">公開する</a>
            @endif
            {{-- リターンを作成 --}}
            @if (Auth::guard(\App\Consts\UserConst::GUARD)->check() &&
    Auth::guard(\App\Consts\UserConst::GUARD)->user()->can('update', $plan))
                <a href="{{ route('plans.gifts.create', $plan) }}"
                    class="bg-gradient-to-r from-indigo-500 to-blue-600 hover:bg-gradient-to-l hover:from-blue-500 hover:to-indigo-600 text-gray-100 p-2 rounded-full tracking-wide font-semibold shadow-lg cursor-pointer transition ease-in duration-500 w-full sm:w-32 sm:mr-2 mb-2 sm:mb-0">リターン作成</a>
            @endif
            {{-- プロジェクトを編集 --}}
            @if (Auth::guard(\App\Consts\UserConst::GUARD)->check() &&
    Auth::guard(\App\Consts\UserConst::GUARD)->user()->can('update', $plan))
                <a href="{{ route('plans.edit', $plan) }}"
                    class="bg-gradient-to-r from-indigo-500 to-blue-600 hover:bg-gradient-to-l hover:from-blue-500 hover:to-indigo-600 text-gray-100 p-2 rounded-full tracking-wide font-semibold shadow-lg cursor-pointer transition ease-in duration-500 w-full sm:w-32 sm:mr-2 mb-2 sm:mb-0">編集</a>
            @endif
            {{-- プロジェクトを削除 --}}
            @if (Auth::guard(\App\Consts\UserConst::GUARD)->check() &&
    Auth::guard(\App\Consts\UserConst::GUARD)->user()->can('delete', $plan))
                <form action="{{ route('plans.destroy', $plan) }}" method="post" class="w-full sm:w-32">
                    @csrf
                    @method('DELETE')
                    <input type="submit" value="削除" onclick="if(!confirm('プロジェクトを削除しますか？')){return false};"
                        class="bg-gradient-to-r from-pink-500 to-purple-600 hover:bg-gradient-to-l hover:from-purple-500 hover:to-pink-600 text-gray-100 p-2 rounded-full tracking-wide font-semibold shadow-lg cursor-pointer transition ease-in duration-500 w-full sm:w-32">
                </form>
            @endif
        </div>
        <br>
        <hr class="grid grid-cols-1 divide-y divide-blue-700 my-6">
        <br>
        <p class="text-gray-700 text-2xl">このプロジェクトのリターン
        </p>
        @foreach ($gifts as $gift) {{-- リターンを表示 --}}
            <div class="md:flex border-2 border-gray-300 max-w-2xl object-contain px-4">
                <div class="md:flex-shrink-0">
                    <img class="rounded-lg md:w-56" src="{{ $gift->imageUrl }}" width="448" height="299"
                        alt="image return">
                </div>
                <div class="mt-4 md:mt-0 md:ml-6">
                    <p class="block mt-1 text-lg leading-tight font-semibold text-gray-900 hover:underline">
                        {{ $gift->name }}
                        @if ($gift->limited_befor == 1)
                            事前登録限定
                        @endif
                    </p>
                    <p class="mt-2 text-gray-600">{{ $gift->description }}</p>
                    <p class="mt-2 text-gray-600">金額: {{ $gift->price }}</p>
                </div>
                {{-- リターンの編集と削除 --}}
                <div class="flex flex-col sm:flex-row items-center sm:justify-end text-center my-4">
                    @if (Auth::guard(\App\Consts\UserConst::GUARD)->check() &&
    Auth::guard(\App\Consts\UserConst::GUARD)->user()->can('update', $plan))
                        <a href="{{ route('plans.gifts.edit', [$plan, $gift]) }}"
                            class="bottom-0 right-0 h-8 bg-gradient-to-r from-indigo-500 to-blue-600 hover:bg-gradient-to-l hover:from-blue-500 hover:to-indigo-600 text-gray-100 p-2 rounded-full tracking-wide font-semibold shadow-lg cursor-pointer transition ease-in duration-500 sm:w-32 sm:mr-2 mb-2 sm:mb-0">編集</a>
                    @endif
                    @if (Auth::guard(\App\Consts\UserConst::GUARD)->check() &&
    Auth::guard(\App\Consts\UserConst::GUARD)->user()->can('delete', $plan))
                        <form action="{{ route('plans.gifts.destroy', [$plan, $gift]) }}" method="post"
                            class="w-full sm:w-32">
                            @csrf
                            @method('DELETE')
                            <input type="submit" value="削除" onclick="if(!confirm('リターンを削除しますか？')){return false};"
                                class="bg-gradient-to-r from-pink-500 to-purple-600 hover:bg-gradient-to-l hover:from-purple-500 hover:to-pink-600 text-gray-100 p-2 rounded-full tracking-wide font-semibold shadow-lg cursor-pointer transition ease-in duration-500 w-full sm:w-32">
                        </form>
                    @endif
                    {{-- 支援周りの表示 --}}
                    {{-- 募集期間終了か、募集開始で基準を満たせなかったものは支援不可 --}}
                    @if (Auth::guard(\App\Consts\fundConst::GUARD)->check() && ($plan->endFlag == true || ($plan->startFlag == false && $plan->releseFlag == true)))
                        <p>受付を終了しました</p>
                    @else
                        {{-- 募集開始後の日付では、限定のものは支援させない --}}
                        @if (Auth::guard(\App\Consts\fundConst::GUARD)->check() && $gift->limited_befor == 1 && $plan->releseFlag == true)
                            <p>募集開始前のみ購入できます</p>
                        @else
                            @if (Auth::guard(\App\Consts\fundConst::GUARD)->check())
                                <a href="{{ route('supports.create', [$plan, $gift]) }}"
                                    class="w-full flex justify-center bg-gradient-to-r from-purple-500 to-blue-600 hover:bg-gradient-to-l hover:from-purple-700 hover:to-blue-800 text-gray-100 p-2 rounded-full tracking-wide font-semibold shadow-lg cursor-pointer transition ease-in duration-500">
                                    @if ($plan->releseFlag)
                                        購入する
                                    @else
                                        事前登録
                                    @endif
                                </a>
                            @endif
                        @endif
                    @endif
                    {{-- @if (Auth::guard(\App\Consts\fundConst::GUARD)->check())
                        <a href="{{ route('supports.create', [$plan, $gift]) }}"
                            class="bottom-0 right-0 h-8 bg-gradient-to-r from-indigo-500 to-blue-600 hover:bg-gradient-to-l hover:from-blue-500 hover:to-indigo-600 text-gray-100 p-2 rounded-full tracking-wide font-semibold shadow-lg cursor-pointer transition ease-in duration-500 sm:w-32 sm:mr-2 mb-2 sm:mb-0">支援する</a>
                    @endif --}}
                </div>
            </div>
        @endforeach
        @if (count($supports) >= 1 && Auth::guard(\App\Consts\fundConst::GUARD)->check())
            <p class="text-gray-700 text-2xl">購入したリターン
            </p>
            @foreach ($supports as $support)
                <div class="md:flex border-2 border-gray-300 max-w-2xl object-contain px-4">
                    <div class="md:flex-shrink-0">
                        <img class="rounded-lg md:w-56" src="{{ $support->gift->imageUrl }}" width="448" height="299"
                            alt="image return">
                    </div>
                    <div class="mt-4 md:mt-0 md:ml-6">
                        <p class="block mt-1 text-lg leading-tight font-semibold text-gray-900 hover:underline">
                            {{ $support->gift->name }}</p>
                        <p class="mt-2 text-gray-600">{{ $support->gift->description }}</p>
                        <p class="mt-2 text-gray-600">金額: {{ $support->gift->price }}</p>
                    </div>
                    <form action="{{ route('plans.supports.destroy', [$plan, $support]) }}" method="post"
                        class="w-full sm:w-32">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="削除" onclick="if(!confirm('購入を取りやめますか？')){return false};"
                            class="mr-5 bg-gradient-to-r from-pink-500 to-purple-600 hover:bg-gradient-to-l hover:from-purple-500 hover:to-pink-600 text-gray-100 p-2 rounded-full tracking-wide font-semibold shadow-lg cursor-pointer transition ease-in duration-500 w-full sm:w-32">
                    </form>
                </div>
            @endforeach
        @endif
        <div>
            <a href="{{ route('plans.index') }}"
                class="mt-5 w-1/2 flex justify-center bg-gradient-to-r from-indigo-500 to-blue-600 hover:bg-gradient-to-l hover:from-blue-500 hover:to-indigo-600 text-gray-100 p-4 rounded-full tracking-wide font-semibold shadow-lg cursor-pointer transition ease-in duration-500">一覧に戻る</a>
        </div>
    </div>
</x-app-layout>
