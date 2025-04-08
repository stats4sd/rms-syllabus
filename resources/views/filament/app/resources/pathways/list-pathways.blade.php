<div>
    <div class="container-fluid w-full flex flex-wrap bg-darkblue justify-center py-10">

        <div class="w-full sm:w-1/2 px-20">
            <h1 class="text-white pt-6">Choose your learning pathway</h1>
            <!-- <h4 class="text-white font-normal pt-2">Essential research methods for agroecology</h4> -->
        </div>

        <div class="w-full sm:w-1/2 flex justify-center items-center">
            <img src="/images/syllabus_header.png" alt="Image" class="max-w-xs h-auto">
        </div>

    </div>

    <div class="pt-8 py-24 bg-white px-20">
        <h2>Customise your learning journey</h2>
        <div class="divider mt-3"></div>
        <h4 class="font-normal">Explanation text to be drafted.</h4>
        <h4 class="font-normal">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam lacinia sed ipsum sed euismod. Fusce interdum quis 
            ligula molestie egestas. Proin aliquet nec nunc a varius. Duis dui lacus, vestibulum non justo at, sodales 
            lobortis purus. Pellentesque iaculis, urna non pretium bibendum, eros elit mollis dui, sit amet tristique tortor 
            neque a lorem. Etiam eu ante ligula. Nulla vulputate tempus vehicula. Pellentesque in erat at eros aliquam hendrerit 
            in sit amet arcu. Cras sit amet risus nibh. Etiam vel egestas sapien, quis lobortis lacus.</h4>
    </div>

    <!-- pathways banner -->
    <div class="container-fluid w-full flex flex-wrap bg-darkblue justify-center pb-5">
        <div class="w-full px-20">
            <h3 class="text-white pt-6">Available Pathways</h3>
        </div>
    </div>

    <div class="px-20 pb-12">
        <div class="grid grid-cols-1 gap-6 pt-12 px-20">
            @foreach(\App\Models\Pathway::all() as $pathway)
                <a href="{{ route('filament.app.resources.pathways.view', $pathway) }}"
                class="py-6 px-20 text-white bg-darkblue shadow hover-effect">
                    <p class="text-sm font-bold mt-2">PATHWAY</p>
                    <h2 class="text-lg font-semibold">{{ $pathway->name }}</h2>
                    <p class="text-sm pt-2">{{ $pathway->description }}</p>
                </a>
            @endforeach
        </div>
    </div>

</div>