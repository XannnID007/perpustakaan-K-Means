@extends('layouts.admin')

@section('title', 'K-Means Clustering')
@section('subtitle', 'Pengelompokan pengguna berdasarkan pola membaca')

@section('content')
    <div class="space-y-6">
        <!-- Status Card -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Status Clustering</h3>
                    <button onclick="refreshStatus()" class="btn btn-outline text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        Refresh
                    </button>
                </div>

                <div id="clustering-status" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded">
                        <div class="text-2xl font-bold text-gray-400">-</div>
                        <div class="text-sm text-gray-600">Total User</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded">
                        <div class="text-2xl font-bold text-gray-400">-</div>
                        <div class="text-sm text-gray-600">Cluster Aktif</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded">
                        <div class="text-2xl font-bold text-gray-400">-</div>
                        <div class="text-sm text-gray-600">Terakhir Update</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex space-x-4">
            <button onclick="runClustering()" class="btn btn-primary" id="run-clustering-btn">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z">
                    </path>
                </svg>
                Jalankan Clustering
            </button>

            <button onclick="viewResults()" class="btn btn-secondary" id="view-results-btn" disabled>
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                Lihat Hasil
            </button>
        </div>

        <!-- Results Section -->
        <div id="clustering-results" class="hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Cluster cards will be populated here -->
            </div>
        </div>

        <!-- Loading -->
        <div id="loading" class="hidden text-center py-8">
            <div class="inline-block w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin">
            </div>
            <p class="mt-2 text-gray-600">Sedang menjalankan clustering...</p>
        </div>

        <!-- Log Section -->
        <div class="card">
            <div class="card-body">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Log Aktivitas</h3>
                <div id="activity-log" class="space-y-2 max-h-64 overflow-y-auto">
                    <p class="text-sm text-gray-500">Belum ada aktivitas</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load status saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            refreshStatus();
        });

        function refreshStatus() {
            fetch('/admin/clustering/status')
                .then(response => response.json())
                .then(data => {
                    updateStatusDisplay(data);
                    if (data.clusters_exist) {
                        document.getElementById('view-results-btn').disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    addLog('Error: Gagal memuat status', 'error');
                });
        }

        function updateStatusDisplay(data) {
            const statusDiv = document.getElementById('clustering-status');
            const distribution = data.cluster_distribution || {};
            const totalUsers = data.total_clustered_users || 0;
            const clusterCount = Object.keys(distribution).length;

            statusDiv.innerHTML = `
                <div class="text-center p-4 bg-blue-50 rounded">
                    <div class="text-2xl font-bold text-blue-600">${totalUsers}</div>
                    <div class="text-sm text-blue-600">Total User</div>
                </div>
                <div class="text-center p-4 bg-green-50 rounded">
                    <div class="text-2xl font-bold text-green-600">${clusterCount}</div>
                    <div class="text-sm text-green-600">Cluster Aktif</div>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded">
                    <div class="text-sm font-bold text-purple-600">${data.last_updated || 'Never'}</div>
                    <div class="text-sm text-purple-600">Terakhir Update</div>
                </div>
            `;
        }

        function runClustering() {
            const btn = document.getElementById('run-clustering-btn');
            const loading = document.getElementById('loading');

            btn.disabled = true;
            loading.classList.remove('hidden');
            addLog('Memulai proses clustering...', 'info');

            fetch('/admin/clustering/run', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        addLog(`Clustering berhasil! ${data.users_clustered} user dikelompokkan ke dalam ${data.clusters_count} cluster`,
                            'success');
                        refreshStatus();
                        displayClusterResults(data.profiles);
                        document.getElementById('view-results-btn').disabled = false;
                    } else {
                        addLog('Error: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    addLog('Error: Gagal menjalankan clustering', 'error');
                })
                .finally(() => {
                    btn.disabled = false;
                    loading.classList.add('hidden');
                });
        }

        function viewResults() {
            fetch('/admin/clustering/status')
                .then(response => response.json())
                .then(data => {
                    if (data.profiles) {
                        displayClusterResults(data.profiles);
                    }
                });
        }

        function displayClusterResults(profiles) {
            const resultsDiv = document.getElementById('clustering-results');
            const clusterColors = ['bg-blue-50', 'bg-green-50', 'bg-purple-50'];
            const textColors = ['text-blue-700', 'text-green-700', 'text-purple-700'];

            let html = '';
            Object.entries(profiles).forEach(([clusterId, profile], index) => {
                const bgColor = clusterColors[index % clusterColors.length];
                const textColor = textColors[index % textColors.length];

                html += `
                    <div class="card ${bgColor}">
                        <div class="card-body">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold ${textColor}">${profile.name}</h4>
                                <span class="px-2 py-1 ${bgColor} ${textColor} rounded-full text-sm font-medium">
                                    ${profile.user_count} user
                                </span>
                            </div>
                            
                            <p class="text-sm text-gray-600 mb-4">${profile.description}</p>
                            
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Preferensi Fiksi:</span>
                                    <span class="font-medium">${Math.round(profile.avg_fiction_ratio * 100)}%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Rata-rata Buku:</span>
                                    <span class="font-medium">${Math.round(profile.avg_total_books)} buku</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Rata-rata Rating:</span>
                                    <span class="font-medium">${profile.avg_rating.toFixed(1)}/5</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            resultsDiv.innerHTML = html;
            resultsDiv.classList.remove('hidden');
            addLog('Hasil clustering ditampilkan', 'success');
        }

        function addLog(message, type = 'info') {
            const logDiv = document.getElementById('activity-log');
            const timestamp = new Date().toLocaleTimeString();
            const colors = {
                info: 'text-blue-600',
                success: 'text-green-600',
                error: 'text-red-600'
            };

            const logEntry = document.createElement('div');
            logEntry.className = `text-sm ${colors[type] || colors.info}`;
            logEntry.innerHTML = `<span class="text-gray-400">[${timestamp}]</span> ${message}`;

            // Insert at the beginning
            if (logDiv.firstChild && logDiv.firstChild.textContent !== 'Belum ada aktivitas') {
                logDiv.insertBefore(logEntry, logDiv.firstChild);
            } else {
                logDiv.innerHTML = '';
                logDiv.appendChild(logEntry);
            }

            // Keep only last 10 entries
            while (logDiv.children.length > 10) {
                logDiv.removeChild(logDiv.lastChild);
            }
        }
    </script>
@endsection
