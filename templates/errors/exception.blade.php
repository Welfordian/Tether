@extends('layouts.exception', ['exception' => $exception])

@section('content')
    <div class="flex justify-center bg-gray-900 text-white" id="app">
        <div class="flex flex-col w-3/4 mt-7">
            <div class="w-full py-7 px-8 bg-gray-800 justify-between flex flex-col gap-5">
                <div class="flex gap-6 items-start justify-between font-bold w-full break-words">
                    <span class="py-3 px-6 bg-gray-900">{{ $exception::class }}</span>

                    <span class="py-3 px-6 bg-gray-700">PHP {{ phpversion() }}</span>
                </div>
                
                <div class="flex flex-col gap-6 items-start font-bold">
                    <p class="text-2xl break-words w-full">{{ $exception->getMessage() }}</p>
                </div>
            </div>

            <div class="flex mt-8 grow h-screen">
                <div class="w-1/4 h-screen bg-gray-800 flex flex-col text-white border-r-2 border-gray-700">
                    <div class="gap-1 h-screen max-h-screen overflow-y-auto">
                        <div
                                class="w-full px-4 py-4 text-sm bg-gray-800 cursor-pointer hover:bg-gray-700 break-words max-w-full transition"
                                :class="{'bg-gray-700': pane === key}"
                                v-for="(trace, key) in traces"
                                v-show="'lines' in trace"
                                @click="pane = key"
                        >
                            @{{ trace.file }}<span class="font-mono text-xs">:@{{ trace.line }}</span>
                        </div>
                    </div>
                </div>

                <div class="grow flex flex-col w-3/4 bg-gray-900 text-white" v-for="(trace, key) in traces" v-show="pane === key">
                    <div class="w-full bg-gray-800 block p-4 overflow-x-auto">
                        <h1>
                            @{{ trace.short_file }}<span class="font-mono text-xs">:@{{ trace.line }}</span>
                        </h1>
                    </div>

                    <div class="overflow-auto">
                        <pre class="h-screen max-h-screen w-full max-w-full group">
                            <code class="bg-gray-900 group-hover:blur-none transition ease duration-200"
                                  v-for="(line, key) in trace.lines"
                                  :class="{'bg-white/10 p-3': key === 9, 'blur-[2px]': key !== 9}"
                            >@{{ line }}</code>
                        </pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

    <script>
        const { createApp } = Vue

        createApp({
            data() {
                return {
                    pane: 0,
                    traces: {!! json_encode($trace) !!}
                }
            }
        }).mount('#app')
    </script>
@endsection