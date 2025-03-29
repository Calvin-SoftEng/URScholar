<template>
    <div class="flex flex-col gap-2 w-full h-1/12 justify-center items-center">
        <span class="text-4xl font-bold">Tulong Dunong Program</span>
        <span class="text-xl">Grantee</span>
    </div>
    <div class="flex items-center justify-center my-8 ">
        <!-- Step 1 -->
        <div class="relative flex flex-col items-center">
            <div
                class="w-10 h-10 flex items-center justify-center rounded-full bg-primary border-4 border-primary text-primary font-bold text-lg">
                <font-awesome-icon :icon="['fas', 'check']" class="text-white" />
            </div>
            <span class="mt-2 text-sm text-gray-700">Application</span>
        </div>

        <!-- Line -->
        <div class="w-16 h-1 bg-primary relative -top-4"></div>

        <!-- Step 2 -->
        <div class="relative flex flex-col items-center">
            <div
                class="w-10 h-10 flex items-center justify-center rounded-full bg-white border-4 border-primary text-primary font-bold text-lg">
                2</div>
            <span class="mt-2 text-sm text-gray-700">Under Review</span>
        </div>

        <!-- Line -->
        <div class="w-16 h-1 bg-gray-300 relative -top-4"></div>

        <!-- Step 3 -->
        <div class="relative flex flex-col items-center">
            <div
                class="w-10 h-10 flex items-center justify-center rounded-full bg-white border-4 border-primary text-primary font-bold text-lg">
                3</div>
            <span class="mt-2 text-sm text-gray-700">Approved</span>
        </div>
    </div>
    <!-- <div class="bg-dirtywhite w-full p-3 flex flex-col font-quicksand">
        <span>From Maam Anorn</span>
        <span>Message</span>
        <p>It is noted, however, that among the requirements you have submittted to DBP, the
            following documents must be
            resubmitted.</p>
        <br>
        <span>Message nung nasa return: Ex anlabo pre</span>
        <br>
        <span>Deadline</span>
        <br>
        <form @submit.prevent="submitRequirements" class="space-y-6">
            <div v-for="req in returnedRequirements" :key="req.id"
                class="bg-white border rounded-lg shadow-sm p-4">
                <h3 class="font-medium text-gray-900">{{ req.requirement_name }}</h3>
                <p class="text-sm text-gray-600 mt-1">Return reason: {{ req.return_message }}</p>

                <div class="mt-3">
                    <input type="file" @change="(e) => handleFile(e, req.id, req.requirement_name)"
                        :id="'file_' + req.id" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none hover:bg-gray-100" />

                    <div v-if="selectedFiles[req.id]"
                        class="flex items-center gap-2 text-sm text-gray-600 mt-2">
                        <font-awesome-icon :icon="['fas', 'file']" />
                        <span>{{ selectedFiles[req.id] }}</span>
                        <button type="button" @click="removeFile(req.id)"
                            class="text-red-600 hover:text-red-800">
                            <font-awesome-icon :icon="['fas', 'times']" />
                        </button>
                    </div>

                    <p v-if="form.errors[`files.${req.id}`]" class="mt-1 text-sm text-red-600">
                        {{ form.errors[`files.${req.id}`] }}
                    </p>
                </div>
            </div>

            <div v-if="returnedRequirements.length === 0" class="text-center py-8">
                <p class="text-gray-500">No returned requirements to resubmit.</p>
            </div>

            <div v-if="returnedRequirements.length > 0" class="flex justify-end">
                <button type="submit"
                    :disabled="form.processing || Object.keys(form.files).length === 0"
                    class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none
        focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 
        transition-all duration-300 ease-in-out transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span>{{ form.processing ? 'Submitting...' : 'Submit again' }}</span>
                </button>
            </div>
        </form>
    </div> -->

    <!-- first stepper -->

    <div class="bg-dirtywhite w-full p-3 flex flex-col font-popins text-xl">
        <h1>Congratulations!</h1>
        <p>Your application has been successfully completed.</p>
        <p>You will be notified about the next steps soon.</p>
        <br>
        
    </div>

    <!-- second stepper -->

    <div class="bg-dirtywhite w-full p-3 flex flex-col font-popins text-xl">
        <span>From Maam Anorn</span>
        <span>Message</span>
        <p>It is noted, however, that among the requirements you have submittted to DBP, the
            following documents must be
            resubmitted.</p>
        <br>
        <span>Message nung nasa return: Ex anlabo pre</span>
        <br>
        <span>Deadline</span>
        <br>
        <form @submit.prevent="submitRequirements" class="space-y-6">
            <div v-for="req in returnedRequirements" :key="req.id"
                class="bg-white border rounded-lg shadow-sm p-4">
                <h3 class="font-medium text-gray-900">{{ req.requirement_name }}</h3>
                <p class="text-sm text-gray-600 mt-1">Return reason: {{ req.return_message }}</p>

                <div class="mt-3">
                    <input type="file" @change="(e) => handleFile(e, req.id, req.requirement_name)"
                        :id="'file_' + req.id" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none hover:bg-gray-100" />

                    <div v-if="selectedFiles[req.id]"
                        class="flex items-center gap-2 text-sm text-gray-600 mt-2">
                        <font-awesome-icon :icon="['fas', 'file']" />
                        <span>{{ selectedFiles[req.id] }}</span>
                        <button type="button" @click="removeFile(req.id)"
                            class="text-red-600 hover:text-red-800">
                            <font-awesome-icon :icon="['fas', 'times']" />
                        </button>
                    </div>

                    <p v-if="form.errors[`files.${req.id}`]" class="mt-1 text-sm text-red-600">
                        {{ form.errors[`files.${req.id}`] }}
                    </p>
                </div>
            </div>

            <div v-if="returnedRequirements.length === 0" class="text-center py-8">
                <p class="text-gray-500">No returned requirements to resubmit.</p>
            </div>

            <div v-if="returnedRequirements.length > 0" class="flex justify-end">
                <button type="submit"
                    :disabled="form.processing || Object.keys(form.files).length === 0"
                    class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none
        focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 
        transition-all duration-300 ease-in-out transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span>{{ form.processing ? 'Submitting...' : 'Submit again' }}</span>
                </button>
            </div>
        </form>
    </div>

    <!-- third stepper -->

    <div class="bg-dirtywhite w-full p-3 flex flex-col font-popins text-xl">
        <h1>Congratulations!</h1>
        <p>Your application has been successfully completed.</p>
        <p>You will be notified about the payout announcement soon.</p>
        <br>
        
    </div>
</template>

<script setup>

</script>