<script setup>
// This starter template is using Vue 3 <script setup> SFCs
// Check out https://vuejs.org/api/sfc-script-setup.html#script-setup
import AssayClassIndex from '@/views/AssayClassIndex.vue'
</script>

<template>
  <div>
    <header id="nav" class="border-b bg-gray-100 print:hidden">
      <div class="container py-3 flex">
        <div id="main-menu" class="flex space-x-4 flex-grow">
          <div class="inline-block pr-4">
            <router-link to="/" class="black">FuncEvInt</router-link>
          </div>
          
          <router-link to="/functional-assays" class="black block ">Functional Assays</router-link> 
          <router-link to="/assay-classes" class="black block ">Assay Classes</router-link> 
          <router-link to="/coding-systems" class="black block ">Coding Systems</router-link>
          <router-link to="/publications" class="black block ">Publications</router-link>

          <DropdownMenu orientation="left">
            <template #label>Dev</template>
            <DropdownItem>
              <router-link 
                to="/dev/style-guide" 
                class="black block " 
              >Style Guide</router-link> 
              <router-link 
                to="/dev/form-example" 
                class="black block " 
              >Form Example</router-link> 
            </DropdownItem>
          </DropdownMenu>
        </div>
      </div>
    </header>

    <div class="my-6 container">
      <div>
        <div>
          <router-view />
        </div>
      </div>
    </div>
  </div>
</template>

