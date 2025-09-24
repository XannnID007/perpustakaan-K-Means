@extends('layouts.admin')

@section('title', 'Evaluasi Kualitas Clustering')
@section('subtitle', 'Hasil evaluasi clustering menggunakan Davies-Bouldin Index.')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Hasil Evaluasi - Davies-Bouldin Index (DBI)</h3>
                    <p class="mt-2 text-sm text-gray-600">
                        Metrik ini mengukur kualitas struktur cluster. <strong>Semakin rendah nilainya (mendekati 0),
                            semakin baik hasil clusteringnya.</strong>
                    </p>

                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    Davies-Bouldin Index
                                </dt>
                                <dd class="mt-1 text-2xl font-bold text-indigo-600 sm:mt-0 sm:col-span-2">
                                    {{-- Pastikan variabel ini dikirim dari controller --}}
                                    @if (isset($daviesBouldinIndex))
                                        {{ number_format($daviesBouldinIndex, 4) }}
                                    @else
                                        Data tidak tersedia
                                    @endif
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    Interpretasi
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    @if (isset($daviesBouldinIndex))
                                        @if ($daviesBouldinIndex < 1)
                                            <span class="font-semibold text-green-700">Hasil clustering sangat baik (cluster
                                                padat dan terpisah).</span>
                                        @else
                                            <span class="font-semibold text-yellow-700">Hasil clustering cukup baik, namun
                                                mungkin ada tumpang tindih.</span>
                                        @endif
                                    @endif
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    Detail
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    @if (isset($totalData) && isset($nClusters))
                                        Dievaluasi pada <strong>{{ $totalData }}</strong> data pengguna dengan
                                        <strong>{{ $nClusters }}</strong> cluster.
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
