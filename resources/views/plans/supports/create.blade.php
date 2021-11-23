<x-app-layout>
    <div class="container lg:w-3/4 md:w-4/5 w-11/12 mx-auto my-8 px-4 py-4 bg-white shadow-md">
        <x-validation-errors :errors="$errors" />
        <div class="md:flex border-2 border-gray-300 max-w-2xl object-contain px-4">
            <div class="md:flex-shrink-0">
                <img class="rounded-lg md:w-56" src="{{ $gift->imageUrl }}" width="448" height="299"
                    alt="image return">
            </div>
            <div class="mt-4 md:mt-0 md:ml-6">
                <p class="block mt-1 text-lg leading-tight font-semibold text-gray-900 hover:underline">
                    {{ $gift->name }}</p>
                <p class="mt-2 text-gray-600">{{ $gift->description }}</p>
                <p class="mt-2 text-gray-600">金額: {{ $gift->price }}</p>
            </div>
        </div>

        <div>
            <p>{{ $plan->title }}の{{ $gift->name }}に{{ $gift->price }}支援します</p>
        </div>
    </div>
</x-app-layout>
