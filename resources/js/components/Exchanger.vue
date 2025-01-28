<template>

    <div class="overflow-hidden overflow-x-auto p-6 bg-white border-gray-200">
        <div class="min-w-full align-middle">
            <form class="w-full max-w-lg">
                <div class="flex flex-wrap -mx-3 mb-2">
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                            From
                        </label>
                        <div class="relative">
                            <select v-model="curr_from" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                                <option selected>EUR</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                            To
                        </label>
                        <div class="relative">
                            <select v-model="curr_to" @change="table = false" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                                <option>USD</option>
                                <option>GBP</option>
                                <option>AUD</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-zip">
                            Amount
                        </label>
                        <input v-model="amount" disabled name="amount" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" type="text" placeholder="">
                    </div>
                    <div class="w-full px-3 mb-6 mt-3 md:mb-0">
                        <button @click="exchange()" class="bg-gray-500 w-full hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                            Exchange
                        </button>
                    </div>

                </div>
            </form>

            <div v-if="loader" class="text-center mt-6">
                <div class="inline-block h-12 w-12 animate-spin rounded-full border-4 border-solid border-current border-e-transparent align-[-0.125em] text-surface motion-reduce:animate-[spin_1.5s_linear_infinite] text-blue-500" role="status">
                  <span class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                </div>
            </div>

            <div v-if="table && loader == false">
                <table class="min-w-full divide-y divide-gray-200 border">
                    <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-center">
                            <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Date</span>
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-center">
                        <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            {{ curr_from }} to {{ curr_to }}
                        </span>
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                    <tr v-for="one in list">
                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 text-center">
                            {{ one.date }}
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 text-center">
                            {{ one.to_amount }}
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div>
                    &nbsp;
                </div>

                <div class="text-center text-sm">
                    Minimum: {{ this.min }} {{ curr_to}}<br>
                    Maximum: {{ this.max }} {{ curr_to}}<br>
                    Average: {{ this.avg }} {{ curr_to}}<br><br>
                    Last update: {{ this.lastUpdate }}
                </div>
            </div>

        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            sortKey: 'name',
            list : [],
            table : false,
            curr_from: 'EUR',
            curr_to: 'USD',
            amount: 1,
            loader: false,
            min: 0,
            max: 0,
            avg: 0,
            lastUpdate: '',
        }
    },
    mounted() {
        this.fetchPosts()
    },
    methods: {
        fetchPosts() {
            // axios.get('/api/get_data')
            //     .then(response => this.list = response.data)
            //     .catch(error => console.log(error))
        },
        exchange() {
            this.loader = true;
            const api = axios.create({baseURL: 'api/'})
            api.post('get_data', {
                curr_from: this.curr_from,
                curr_to: this.curr_to,
                amount: this.amount,
            })
                .then(res => {
                    let data = res.data;
                    console.log(data);
                    if (!data.errors) this.table = true;
                    this.list = data.list
                    this.min = data.min
                    this.max = data.max
                    this.avg = data.avg
                    this.lastUpdate = data.lastUpdate
                    this.loader = false
                })
                .catch(error => {
                    console.log(error)
                    this.list = [];
                    this.loader = false
                })
        },
    },
}
</script>
