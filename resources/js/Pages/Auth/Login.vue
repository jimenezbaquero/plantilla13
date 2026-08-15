<template>
  <GuestLayout>
    <Head title="Log in" />
    
    <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
      {{ status }}
    </div>
    
    <a
      :href="route('social.auth', { provider: 'google' })"
      class="mb-4 flex w-full items-center justify-center gap-3 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
    >
      <svg
        class="h-5 w-5"
        viewBox="0 0 24 24"
        aria-hidden="true"
      >
        <path
          fill="#4285F4"
          d="M21.35 12.27c0-.71-.06-1.4-.18-2.06H12v3.9h5.24a4.48 4.48 0 0 1-1.94 2.94v2.45h3.14c1.84-1.69 2.91-4.18 2.91-7.23z"
        />
        <path
          fill="#34A853"
          d="M12 21.5c2.63 0 4.84-.87 6.45-2.35l-3.14-2.45c-.87.58-1.98.92-3.31.92-2.54 0-4.7-1.72-5.47-4.04H3.29v2.53A9.74 9.74 0 0 0 12 21.5z"
        />
        <path
          fill="#FBBC05"
          d="M6.53 13.58A5.86 5.86 0 0 1 6.22 12c0-.55.11-1.09.31-1.58V7.89H3.29A9.5 9.5 0 0 0 2.25 12c0 1.53.37 2.98 1.04 4.11l3.24-2.53z"
        />
        <path
          fill="#EA4335"
          d="M12 6.38c1.43 0 2.71.49 3.72 1.45l2.79-2.79C16.84 3.45 14.63 2.5 12 2.5a9.74 9.74 0 0 0-8.71 5.39l3.24 2.53c.77-2.32 2.93-4.04 5.47-4.04z"
        />
      </svg>
      
      <span>Continue with Google</span>
    </a>
    
    <div class="mb-4 flex items-center">
      <div class="flex-1 border-t border-gray-300"></div>
      <span class="px-3 text-sm text-gray-500">or</span>
      <div class="flex-1 border-t border-gray-300"></div>
    </div>
    
    <form @submit.prevent="submit">
      <div>
        <InputLabel for="email" value="Email" />
        
        <TextInput
          id="email"
          type="email"
          class="mt-1 block w-full"
          v-model="form.email"
          required
          autofocus
          autocomplete="username"
        />
        
        <InputError class="mt-2" :message="form.errors.email" />
      </div>
      
      <div class="mt-4">
        <InputLabel for="password" value="Password" />
        
        <TextInput
          id="password"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password"
          required
          autocomplete="current-password"
        />
        
        <InputError class="mt-2" :message="form.errors.password" />
      </div>
      
      <div class="mt-4 block">
        <label class="flex items-center">
          <Checkbox
            name="remember"
            v-model:checked="form.remember"
          />
          <span class="ms-2 text-sm text-gray-600">
                        Remember me
                    </span>
        </label>
      </div>
      
      <div class="mt-4 flex items-center justify-end">
        <Link
          v-if="canResetPassword"
          :href="route('password.request')"
          class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
          Forgot your password?
        </Link>
        
        <PrimaryButton
          class="ms-4"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Log in
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>

<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
  canResetPassword: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};
</script>