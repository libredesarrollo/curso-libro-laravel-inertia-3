<script setup lang="ts">
import { Head, Form, Link } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
} from '@/components/ui/card';
import { ArrowLeft, Save } from 'lucide-vue-next';
import {
    update,
    store,
} from '@/actions/App/Http/Controllers/Dashboard/CategoryController';

defineProps<{
    category: {
        id?: number;
        title?: string;
        slug?: string;
    };
}>();
</script>

<template>
    <Head :title="category.id ? 'Edit Category' : 'Create Category'" />

    <div class="mx-auto max-w-2xl space-y-6">
        <div class="flex items-center gap-4">
            <Button variant="ghost" size="sm" as-child>
                <Link :href="store().url">
                    <ArrowLeft class="mr-2 h-4 w-4" />
                    Back
                </Link>
            </Button>
        </div>

        <Heading
            :title="category.id ? 'Edit Category' : 'Create Category'"
            description="Fill in the details below"
        />

        <Form
            v-bind="category.id ? update.form(category.id) : store.form()"
            class="space-y-0"
            v-slot="{ errors, processing, recentlySuccessful }"
        >
            <Card>
                <CardHeader class="space-y-1 pb-4">
                    <h3 class="text-lg font-medium">Category Information</h3>
                    <p class="text-sm text-muted-foreground">
                        Enter the category details.
                    </p>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="space-y-2">
                        <label
                            for="title"
                            class="text-sm leading-none font-medium peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                        >
                            Title <span class="text-destructive">*</span>
                        </label>
                        <input
                            id="title"
                            name="title"
                            :value="category.title"
                            required
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                        />
                        <InputError :message="errors.title" />
                    </div>

                    <div class="space-y-2">
                        <label
                            for="slug"
                            class="text-sm leading-none font-medium peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                        >
                            Slug
                        </label>
                        <input
                            id="slug"
                            name="slug"
                            :value="category.slug"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 font-mono text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                        />
                        <InputError :message="errors.slug" />
                    </div>
                </CardContent>
                <CardFooter
                    class="flex items-center justify-between border-t pt-6"
                >
                    <p class="text-sm text-muted-foreground">
                        Fields marked with
                        <span class="text-destructive">*</span> are required.
                    </p>
                    <div class="flex items-center gap-3">
                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <span
                                v-if="recentlySuccessful"
                                class="text-sm text-green-600 dark:text-green-400"
                            >
                                Saved.
                            </span>
                        </Transition>
                        <Button type="submit" :disabled="processing">
                            <Save class="mr-2 h-4 w-4" />
                            {{ processing ? 'Saving...' : 'Save' }}
                        </Button>
                    </div>
                </CardFooter>
            </Card>
        </Form>
    </div>
</template>
