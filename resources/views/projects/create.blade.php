@extends ('layouts.app')

@section('content')
    <form method="post" action="/projects" style="padding-top: 40px">
        @csrf
        <h1 class="heading is-1">Create a Project</h1>
        <div class="field">
            <label class="label" for="">Title</label>

            <div class="control">
                <input type="text" class="input" name="title" placeholder="Title">
            </div>
        </div>

        <div class="field">
            <label class="label" for="description">Description</label>

            <div class="control">
                <textarea name="description" class="textarea"></textarea>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <button type="submit" class="bg-blue button is-link">Create a Project</button>
                <a href="/projects">Cancel</a>
            </div>
        </div>
    </form>
@endsection
