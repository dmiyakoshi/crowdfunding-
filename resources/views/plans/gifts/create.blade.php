<x-app-layout>
    <div class="container lg:w-1/2 md:w-4/5 w-11/12 mx-auto mt-8 px-8 bg-blue-400 shadow-md rounded-md">
        <h2 class="text-center text-lg text-white font-bold pt-6 pm-6 tracking-widest">リターン登録</h2>
        <x-validation-errors :errors="$errors" />
    </div>

    <form action="{{ route('plans.gifts.store', $plan) }}" method="POST" class="rounded pt-3 pb-8 mb-4"
        enctype="multipart/form-data">
        @csrf
        <div class="container lg:w-1/2 md:w-4/5 w-11/12 mx-auto mt-8 px-8 bg-blue-400 shadow-md rounded-md">
            <div class="mb-4">
                <label class="block text-white mb-2" for="name">
                    リターンのタイトル
                </label>
                <input type="text" name="name"
                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-blue-800 w-full py-2 px-3"
                    required placeholder="20字以上40字以下" value="{{ old('name') }}">
                <label class="block text-white mb-2" for="money">
                    金額
                </label>
                <input type="text" name="money"
                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-blue-800 w-full py-2 px-3"
                    required placeholder="目標額を記入" value="{{ old('money') }}">
            </div>
            <div class="mb-4">
                <label class="block text-white mb-2" for="content">
                    リターンの説明
                </label>
                <textarea name="introduction" rows="10"
                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-blue-800 w-full py-2 px-3"
                    required placeholder="250文字以上??文字以下">{{ old('content') }}</textarea>
            </div>
            {{-- <div class="mb-4"> //複数画像は記事のどこに表示するか情報がないので後回し
                <label class="block text-white mb-2" for="file">画像</label>
                <input type="file" name="file[]" id="file" class="" multiple>
            </div> --}}
            <div class="mb-4">
                <label class="block text-white mb-2" for="file">画像</label>
                <input type="file" name="file" id="file" class="">
            </div>
            <div class="container lg:w-1/2 md:w-4/5 w-11/12 mx-auto mt-8 px-8">
                <input type="submit" value="登録"
                    class="w-full flex justify-center bg-gradient-to-r from-purple-500 to-blue-600 hover:bg-gradient-to-l hover:from-purple-700 hover:to-blue-800 text-gray-100 p-2 rounded-full tracking-wide font-semibold shadow-lg cursor-pointer transition ease-in duration-500">
            </div>
        </div>
    </form>
</x-app-layout>
