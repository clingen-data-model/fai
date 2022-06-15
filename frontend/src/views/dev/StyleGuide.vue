<script setup></script>
<template>
    <div>
        <h1>Style Guide</h1>
        <hr>
        <div class="flex space-x-4">
            <div class="component-group">
                <h2>Headings</h2>
                <hr>
                <h1>Heading 1</h1>
                <h2>Heading 2</h2>
                <h3>Heading 3</h3>
                <h4>Heading 4</h4>
                <h5>Heading 5</h5>
            </div>
            <div class="component-group">
                <h2>Forms</h2>
                <hr>
                <input type="text" class="block">
                <input type="checkbox" class="block">
                <input type="radio" class="block">
                <textarea></textarea>
                <br>
                <select>
                    <option value="...">Select...</option>
                    <option value="option1">Option 1</option>
                </select>
            </div>
            <div class="component-group">
                <h2>Buttons</h2>
                <hr>
                <button class="btn lg">Large button</button>
                <br>
                <button class="btn">Regular button</button>
                <br>
                <button class="btn sm">Small button</button>
                <br>
                <button class="btn xs">XS button</button>
                <br>
                <button class="btn blue">Blue button</button>
                <br>
                <button class="btn red">red button</button>
                <br>
                <button class="btn yellow">yellow button</button>
                <br>
                <a href="#" class="btn">link as button</a>
                <br>
                <button class="link">Button as link</button>
            </div>
        </div>
    </div>
</template>
<style scoped>
    .component-group {
        @apply space-y-2 flex-grow
    }
</style>