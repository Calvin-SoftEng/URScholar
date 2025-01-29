<template>
    <!-- Overlay to detect clicks outside -->
    <Transition name="slide">
        <div 
          v-show="showPanel" 
          class="fixed inset-0 z-50 flex justify-end"
          @click.self="closePanel" 
        >
          <!-- Right side panel -->
          <div 
            class="h-full overflow-y-auto bg-white w-4/12 shadow-lg transition-transform duration-300"
            @click.stop 
          >
            <div class="p-4 border-b flex justify-between items-center">
              <h2 class="text-xl font-semibold">Add Scholars</h2>
              <button 
                @click="closePanel"
                class="p-2 hover:bg-gray-100 rounded-full transition-colors"
              >
                <span class="material-symbols-rounded">close</span>
              </button>
            </div>
    
            <!-- File Upload -->
            <div class="card">
              <FileUpload 
                name="demo[]" 
                @uploader="onUpload" 
                :multiple="true" 
                accept=".csv" 
                :maxFileSize="1000000"
                customUpload 
                @select="handleFileUpload"
                @clear="clearPreview"
                id="file-upload"
              />
            </div>
    
            <!-- Error Message -->
            <div v-if="error" class="text-red-500 mb-4">
              {{ error }}
            </div>
    
            <!-- Preview Table -->
            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm mt-4">
              <table v-if="previewData.length > 0" class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                  <tr>
                    <th v-for="header in headers" :key="header" class="px-6 py-3">
                      {{ header }}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(row, rowIndex) in previewData" :key="rowIndex" class="bg-white border-b hover:bg-gray-50">
                    <td v-for="(header, colIndex) in headers" :key="colIndex" class="px-6 py-4 whitespace-nowrap">
                      {{ row[header] }}
                    </td>
                  </tr>
                </tbody>
              </table>
    
              <!-- Empty State -->
              <div v-if="previewData.length === 0 && !error" class="text-center py-4 text-gray-500">
                No data to display. Please upload a CSV file.
              </div>
            </div>
          </div>
        </div>
      </Transition>
</template>

<script setup>
import { ref, defineProps } from 'vue';
import Papa from 'papaparse';

const showPanel = ref(false)
  
  const toggleCreate = () => {
    showPanel.value = !showPanel.value
  }
  
  const closePanel = () => {
    previewData.value = [];
      headers.value = [];
      document.getElementById('file-upload').value = null;
    showPanel.value = false 
  }
  
  const props = defineProps({
    scholarship: Object,
    scholars: Array
  });
  
  const form = useForm({
    file: null,
  });
  
  const previewData = ref([]);
  const headers = ref([]);
  const error = ref('');
  
  const handleFileUpload = (event) => {  
    // eto ay para mafetch yung file
    const file = event.files[0];
    // eto naman ay para mafetch yung file as object
    if (event && event.files && event.files.length > 0) {
      const file = event.files[0]; // Get first file
  
      const reader = new FileReader();
      
      reader.onload = function(e) {
        Papa.parse(e.target.result, {
          header: true,
          complete: (results) => {
            if (results.data && results.data.length > 0) {
              // Filter out empty rows
              const filteredData = results.data.filter(row => 
                Object.values(row).some(value => value !== '')
              );
              
              if (filteredData.length > 0) {
                headers.value = Object.keys(filteredData[0]);
                previewData.value = filteredData;
                error.value = '';
                console.log('Processed data:', previewData.value); // Debug log
              } else {
                error.value = 'No valid data found in the file';
                previewData.value = [];
                headers.value = [];
              }
            } else {
              error.value = 'No data found in the file';
              previewData.value = [];
              headers.value = [];
            }
          },
          error: (err) => {
            error.value = 'Error parsing CSV: ' + err.message;
            previewData.value = [];
            headers.value = [];
          }
        });
      };
      reader.readAsText(file);
    }
    
  };
  
  const clearPreview = () => {
      previewData.value = [];
      headers.value = [];
    };
  
  const onUpload = async (event) => {
    form.file = event.files[0];
  
    const response = await form.post(`/scholarships/${props.scholarship.id}/upload`);
  
    if (response.ok) {
      headers.value = [];
      previewData.value = [];
      error.value = "";
      document.getElementById('file-upload').value = null; // Clear file input
    } else {
      error.value = "Failed to upload file. Please try again.";
    }
  }
  
  // toast.add({ severity: 'info', summary: 'Success', detail: 'File Uploaded', life: 3000 });
    // form.post(`/scholarships/${props.scholarship.id}/upload`, {
    //   preserveScroll: true,
    // });
  
    // if (response.ok) {
    //         headers.value = [];
    //         previewData.value = [];
    //         error.value = "";
    //         alert("Data inserted successfully!");
    //       } else {
    //         const errorResponse = await response.json();
    //         error.value = errorResponse.message || "Failed to insert data. Please try again.";
    //       }
  
  const uploadCSV = () => {
    form.post(`/scholarships/${props.scholarship.id}/upload`, {
      preserveScroll: true,
    });
  };
</script>

