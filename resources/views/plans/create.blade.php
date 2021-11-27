<x-app-layout>
    <div class="container lg:w-1/2 md:w-4/5 w-11/12 mx-auto mt-8 px-8 bg-blue-400 shadow-md rounded-md">
        <h2 class="text-center text-lg text-white font-bold pt-6 pm-6 tracking-widest">プロジェクト登録</h2>
        <x-validation-errors :errors="$errors" />
    </div>

    <form action="{{ route('plans.store') }}" method="POST" class="rounded pt-3 pb-8 mb-4" enctype="multipart/form-data">
        @csrf
        <div class="container lg:w-1/2 md:w-4/5 w-11/12 mx-auto mt-8 px-8 bg-blue-400 shadow-md rounded-md">
            <div class="mb-4">
                <label class="block text-white mb-2" for="title">
                    プロジェクトのタイトル
                </label>
                <input type="text" name="title"
                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-blue-800 w-full py-2 px-3"
                    required placeholder="20字以上40字以下" value="{{ old('title') }}">
                <label class="block text-white mb-2" for="goal">
                    目標額
                </label>
                <input type="text" name="goal"
                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-blue-800 w-full py-2 px-3"
                    required placeholder="目標額を記入" value="{{ old('goal') }}">
            </div>
            <div class="mb-4">
                <label class="block text-white mb-2" for="introduction">
                    自己紹介と企画の紹介
                </label>
                <textarea name="introduction" rows="10"
                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-blue-800 w-full py-2 px-3"
                    required placeholder="250文字以上??文字以下">{{ old('introduction') }}</textarea>
            </div>
            {{-- <div class="mb-4"> //複数画像は記事のどこに表示するか情報がないので後回し
                <label class="block text-white mb-2" for="file">画像</label>
                <input type="file" name="file[]" id="file" class="" multiple>
            </div> --}}
            <div class="mb-4">
                <label class="block text-white mb-2" for="file">画像</label>
                <input type="file" name="file[]" id="file" class="">
            </div>
        </div>
        <div class="container lg:w-1/2 md:w-4/5 w-11/12 mx-auto mt-8 px-8 bg-blue-400 shadow-md rounded-md">
            <div class="mb-4">
                <label class="block text-white mb-2" for="description_do">
                    何を実現したいか
                </label>
                <label class="block text-white mb-2" for="heading_do">
                    見出し
                </label>
                <input type="text" name="heading_do"
                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-blue-800 w-full py-2 px-3"
                    required placeholder="??字以上？？字以下" value="{{ old('heading_do') }}">
            </div>
            <textarea name="description_do" rows="10"
                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-blue-800 w-full py-2 px-3"
                required placeholder="詳細">{{ old('description_do') }}</textarea>
            {{-- <div class="mb-4">
                <label class="block text-white mb-2" for="file">画像</label>
                <input type="file" name="file[]" id="file" class="" multiple>
            </div> --}}
            <div class="mb-4">
                <label class="block text-white mb-2" for="file">画像</label>
                <input type="file" name="file[]" id="file" class="">
            </div>
        </div>
        <div class="container lg:w-1/2 md:w-4/5 w-11/12 mx-auto mt-8 px-8 bg-blue-400 shadow-md rounded-md">
            <div class="mb-4">
                <label class="block text-white mb-2" for="description_reason">
                    なぜやろうと思ったか
                </label>
                <label class="block text-white mb-2" for="heading_reason">
                    見出し
                </label>
                <input type="text" name="heading_reason"
                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-blue-800 w-full py-2 px-3"
                    required placeholder="??字以上？？字以下" value="{{ old('heading_reason') }}">
            </div>
            <textarea name="description_reason" rows="10"
                class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-blue-800 w-full py-2 px-3"
                required placeholder="詳細">{{ old('description_reason') }}</textarea>
            {{-- <div class="mb-4">
                <label class="block text-white mb-2" for="file">画像</label>
                <input type="file" name="file[]" id="file" class="" multiple>
            </div> --}}
            <div class="mb-4">
                <label class="block text-white mb-2" for="file">画像</label>
                <input type="file" name="file[]" id="file" class="">
            </div>
        </div>
        <div class="container lg:w-1/2 md:w-4/5 w-11/12 mx-auto mt-8 px-8 bg-blue-400 shadow-md rounded-md">
            <div class="mb-4">
                <label class="block text-white mb-2" for="how_use_money">
                    お金の使いみちの説明
                </label>
                <textarea name="how_use_money" rows="10"
                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-blue-800 w-full py-2 px-3"
                    required placeholder="何にいくら必要か示してください">{{ old('how_use_money') }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block text-white mb-2" for="file">画像</label>
                <input type="file" name="file[]" id="file" class="">
            </div>
        </div>
        <div class="container lg:w-1/2 md:w-4/5 w-11/12 mx-auto mt-8 px-8 bg-blue-400 shadow-md rounded-md">
            <div class="mb-4">
                <label class="block text-white mb-2" for="method_id">
                    募集方法
                </label>
                <select name="method_id"
                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-blue-800 w-full py-2 px-3">
                    <option disabled selected value="">選択してください</option>
                    @foreach ($methods as $method)
                        <option value="{{ $method->id }}" @if ($method->id == old('method_id')) selected @endif>{{ $method->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-white mb-2" for="relese_date">
                    募集正式開始日
                </label>
                <input type="date" name="relese_date"
                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-blue-800 w-full py-2 px-3"
                    required placeholder="公開日" value="{{ old('relese_date') }}">
            </div>
            <div class="mb-4">
                <label class="block text-white mb-2" for="due_date">
                    募集期限
                </label>
                <input type="date" name="due_date"
                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-blue-800 w-full py-2 px-3"
                    required placeholder="募集期限" value="{{ old('due_date') }}">
            </div>
            <div class="container lg:w-1/2 md:w-4/5 w-11/12 mx-auto mt-8 px-8">
                <input type="submit" value="登録"
                    class="w-full flex justify-center bg-gradient-to-r from-purple-500 to-blue-600 hover:bg-gradient-to-l hover:from-purple-700 hover:to-blue-800 text-gray-100 p-2 rounded-full tracking-wide font-semibold shadow-lg cursor-pointer transition ease-in duration-500">
            </div>
        </div>
    </form>
</x-app-layout>
