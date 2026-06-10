@props([
'categories',
'level',
'selected' => null,
'existingCategoryId',
])

@forelse ($categories as $category)

<option value="{{ $category->id }}" @selected($category->id == $selected) {{ $category->id == $existingCategoryId ? 'disabled':'' }} >
    @for($i = 0; $i < $level; $i++)
        &nbsp;&nbsp;
    @endfor

    @if($category->parent_id && $level != 0)
        --
    @endif

    {{ $category->name }}
</option>

<x-form.category-select :categories="$category->children" :level="$level + 1"
    :selected="$selected"
    :existingCategoryId="$existingCategoryId"
     />
@empty
@endforelse
