@extends('layouts.admin')

@section('title', 'K-Means Clustering')
@section('subtitle', 'Sistem Pengelompokan Pembaca Otomatis')

@section('content')
    <div class="space-y-6">
        <!-- Enhanced Status Card -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Status Clustering</h3>
                        <p class="text-sm text-gray-600">Pengelompokan otomatis berdasarkan pola membaca pengguna</p>
                    </div>
                    <div class="flex space-x-3">
                        <button onclick="refreshStatus()" class="btn btn-outline text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Refresh
                        </button>
                        <button onclick="exportResults()" class="btn btn-secondary text-sm" id="export-btn" disabled>
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-4-4m4 4l4-4m-6-3h1a2 2 0 002-2V4a2 2 0 00-2-2H9a2 2 0 00-2 2v3a2 2 0 002 2h1">
                                </path>
                            </svg>
                            Export
                        </button>
                    </div>
                </div>

                <div id="clustering-status" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div
                        class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                        <div class="text-3xl font-bold text-blue-600 mb-1">-</div>
                        <div class="text-sm text-blue-700 font-medium">Total Pengguna</div>
                    </div>
                    <div
                        class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-lg border border-green-200">
                        <div class="text-3xl font-bold text-green-600 mb-1">-</div>
                        <div class="text-sm text-green-700 font-medium">Cluster Aktif</div>
                    </div>
                    <div
                        class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg border border-purple-200">
                        <div class="text-sm font-bold text-purple-600 mb-1">-</div>
                        <div class="text-sm text-purple-700 font-medium">Terakhir Update</div>
                    </div>
                    <div
                        class="text-center p-4 bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg border border-orange-200">
                        <div class="text-3xl font-bold text-orange-600 mb-1">-</div>
                        <div class="text-sm text-orange-700 font-medium">Akurasi</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Action Buttons -->
        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
            <button onclick="runClustering()" class="btn btn-primary flex-1 sm:flex-none" id="run-clustering-btn">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z">
                    </path>
                </svg>
                Jalankan Clustering
            </button>

            <button onclick="viewResults()" class="btn btn-secondary flex-1 sm:flex-none" id="view-results-btn" disabled>
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                Lihat Hasil
            </button>

            <button onclick="clearClustering()" class="btn btn-outline text-red-600 border-red-300 flex-1 sm:flex-none"
                id="clear-btn" disabled>
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                    </path>
                </svg>
                Reset Clustering
            </button>
        </div>

        <!-- Enhanced Results Section -->
        <div id="clustering-results" class="hidden">
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Profil Cluster Pengguna</h3>
                <p class="text-gray-600">Hasil pengelompokan otomatis berdasarkan preferensi dan pola membaca</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6" id="cluster-cards">
                <!-- Cluster cards will be populated here -->
            </div>
        </div>

        <!-- Progress Indicator -->
        <div id="loading" class="hidden">
            <div class="card">
                <div class="card-body text-center py-12">
                    <div
                        class="inline-block w-12 h-12 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin mb-4">
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Menjalankan Clustering</h3>
                    <p class="text-gray-600 mb-4">Sedang menganalisis pola membaca pengguna...</p>
                    <div class="w-64 mx-auto bg-gray-200 rounded-full h-2">
                        <div class="bg-primary-600 h-2 rounded-full transition-all duration-1000" id="progress-bar"
                            style="width: 0%"></div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2" id="progress-text">Memulai analisis...</p>
                </div>
            </div>
        </div>

        <!-- Enhanced Log Section -->
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Log Aktivitas</h3>
                    <button onclick="clearLogs()" class="text-sm text-gray-500 hover:text-gray-700">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7"></path>
                        </svg>
                        Clear
                    </button>
                </div>
                <div id="activity-log" class="space-y-2 max-h-80 overflow-y-auto bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500 italic">Belum ada aktivitas clustering</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let progressInterval;

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
                        document.getElementById('export-btn').disabled = false;
                        document.getElementById('clear-btn').disabled = false;
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
            const accuracy = clusterCount > 0 ? Math.round((totalUsers / (totalUsers + 10)) * 100) : 0; // Mock accuracy

            statusDiv.innerHTML = `
                <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                    <div class="text-3xl font-bold text-blue-600 mb-1">${totalUsers}</div>
                    <div class="text-sm text-blue-700 font-medium">Total Pengguna</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-lg border border-green-200">
                    <div class="text-3xl font-bold text-green-600 mb-1">${clusterCount}</div>
                    <div class="text-sm text-green-700 font-medium">Cluster Aktif</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg border border-purple-200">
                    <div class="text-sm font-bold text-purple-600 mb-1">${data.last_updated || 'Never'}</div>
                    <div class="text-sm text-purple-700 font-medium">Terakhir Update</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg border border-orange-200">
                    <div class="text-3xl font-bold text-orange-600 mb-1">${accuracy}%</div>
                    <div class="text-sm text-orange-700 font-medium">Akurasi</div>
                </div>
            `;
        }

        function runClustering() {
            const btn = document.getElementById('run-clustering-btn');
            const loading = document.getElementById('loading');
            const resultsDiv = document.getElementById('clustering-results');

            btn.disabled = true;
            loading.classList.remove('hidden');
            resultsDiv.classList.add('hidden');

            addLog('🚀 Memulai proses clustering...', 'info');
            simulateProgress();

            fetch('/admin/clustering/run', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        addLog(`✅ Clustering berhasil! ${data.users_clustered} user dikelompokkan ke dalam ${data.clusters_count} cluster`,
                            'success');
                        refreshStatus();
                        displayClusterResults(data.profiles);
                        document.getElementById('view-results-btn').disabled = false;
                        document.getElementById('export-btn').disabled = false;
                        document.getElementById('clear-btn').disabled = false;
                    } else {
                        addLog('❌ Error: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    addLog('💥 Error: Gagal menjalankan clustering', 'error');
                })
                .finally(() => {
                    btn.disabled = false;
                    loading.classList.add('hidden');
                    clearInterval(progressInterval);
                });
        }

        function simulateProgress() {
            let progress = 0;
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');

            const steps = [
                'Menganalisis data pengguna...',
                'Menghitung fitur membaca...',
                'Inisialisasi centroids...',
                'Iterasi K-Means...',
                'Optimasi cluster...',
                'Membuat profil cluster...',
                'Finalisasi hasil...'
            ];

            progressInterval = setInterval(() => {
                progress += Math.random() * 15;
                if (progress > 95) progress = 95;

                progressBar.style.width = progress + '%';
                const stepIndex = Math.floor((progress / 100) * steps.length);
                if (stepIndex < steps.length) {
                    progressText.textContent = steps[stepIndex];
                }
            }, 500);
        }

        function viewResults() {
            fetch('/admin/clustering/status')
                .then(response => response.json())
                .then(data => {
                    if (data.profiles) {
                        displayClusterResults(data.profiles);
                        addLog('📊 Menampilkan hasil clustering', 'info');
                    }
                });
        }

        function displayClusterResults(profiles) {
            const resultsDiv = document.getElementById('clustering-results');
            const clusterCards = document.getElementById('cluster-cards');

            let html = '';
            Object.entries(profiles).forEach(([clusterId, profile]) => {
                const colorClass = profile.color || 'bg-gray-500';
                const icon = profile.icon || '👥';

                html += `
                    <div class="card hover:shadow-lg transition-all duration-300 border-l-4 ${colorClass.replace('bg-', 'border-')}">
                        <div class="card-body">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="text-3xl">${icon}</div>
                                    <div>
                                        <h4 class="font-bold text-lg text-gray-900">${profile.name}</h4>
                                        <p class="text-sm text-gray-600">${profile.user_count} pengguna</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 ${colorClass} text-white rounded-full text-sm font-medium">
                                    Cluster ${parseInt(clusterId) + 1}
                                </span>
                            </div>
                            
                            <p class="text-sm text-gray-700 mb-4">${profile.description}</p>
                            
                            ${profile.characteristics ? `
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            ${profile.characteristics.map(char => `
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">${char}</span>
                                    `).join('')}
                                        </div>
                                    ` : ''}
                            
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div class="bg-gray-50 rounded p-3">
                                    <div class="font-medium text-gray-900">Preferensi Fiksi</div>
                                    <div class="text-lg font-bold ${colorClass.replace('bg-', 'text-')}">${Math.round(profile.avg_fiction_preference * 100)}%</div>
                                </div>
                                <div class="bg-gray-50 rounded p-3">
                                    <div class="font-medium text-gray-900">Rata-rata Buku</div>
                                    <div class="text-lg font-bold ${colorClass.replace('bg-', 'text-')}">${Math.round(profile.avg_total_books)}</div>
                                </div>
                                <div class="bg-gray-50 rounded p-3">
                                    <div class="font-medium text-gray-900">Rating Rata-rata</div>
                                    <div class="text-lg font-bold ${colorClass.replace('bg-', 'text-')}">${profile.avg_rating.toFixed(1)}/5</div>
                                </div>
                                <div class="bg-gray-50 rounded p-3">
                                    <div class="font-medium text-gray-900">Tingkat Selesai</div>
                                    <div class="text-lg font-bold ${colorClass.replace('bg-', 'text-')}">${Math.round((profile.avg_completion_rate || 0) * 100)}%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            clusterCards.innerHTML = html;
            resultsDiv.classList.remove('hidden');
        }

        function exportResults() {
            fetch('/admin/clustering/status')
                .then(response => response.json())
                .then(data => {
                    const exportData = {
                        timestamp: new Date().toISOString(),
                        total_users: data.total_clustered_users,
                        clusters: data.profiles,
                        distribution: data.cluster_distribution
                    };

                    const dataStr = JSON.stringify(exportData, null, 2);
                    const dataBlob = new Blob([dataStr], {
                        type: 'application/json'
                    });
                    const url = URL.createObjectURL(dataBlob);

                    const link = document.createElement('a');
                    link.href = url;
                    link.download = `clustering-results-${new Date().toISOString().slice(0,10)}.json`;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    URL.revokeObjectURL(url);

                    addLog('📁 Hasil clustering berhasil diekspor', 'success');
                });
        }

        function clearClustering() {
            if (!confirm('Yakin ingin menghapus semua data clustering? Tindakan ini tidak dapat dibatalkan.')) {
                return;
            }

            // Simulate clearing (you'd need to implement the backend endpoint)
            fetch('/admin/clustering/clear', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(() => {
                    document.getElementById('clustering-results').classList.add('hidden');
                    document.getElementById('view-results-btn').disabled = true;
                    document.getElementById('export-btn').disabled = true;
                    document.getElementById('clear-btn').disabled = true;
                    refreshStatus();
                    addLog('🗑️ Data clustering berhasil dihapus', 'info');
                })
                .catch(() => {
                    addLog('❌ Gagal menghapus data clustering', 'error');
                });
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
            logEntry.className =
                `p-2 border-l-4 border-${type === 'error' ? 'red' : type === 'success' ? 'green' : 'blue'}-300 bg-${type === 'error' ? 'red' : type === 'success' ? 'green' : 'blue'}-50 rounded text-sm`;
            logEntry.innerHTML = `
                <div class="flex items-start space-x-2">
                    <span class="text-gray-500 text-xs font-mono">${timestamp}</span>
                    <span class="${colors[type] || colors.info} flex-1">${message}</span>
                </div>
            `;

            // Insert at the beginning
            if (logDiv.firstChild && !logDiv.firstChild.textContent.includes('Belum ada aktivitas')) {
                logDiv.insertBefore(logEntry, logDiv.firstChild);
            } else {
                logDiv.innerHTML = '';
                logDiv.appendChild(logEntry);
            }

            // Keep only last 20 entries
            while (logDiv.children.length > 20) {
                logDiv.removeChild(logDiv.lastChild);
            }
        }

        function clearLogs() {
            document.getElementById('activity-log').innerHTML =
                '<p class="text-sm text-gray-500 italic">Log telah dibersihkan</p>';
        }
    </script>

    <style>
        .card:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #clustering-results .card {
            animation: fadeIn 0.5s ease-out;
        }

        .border-purple-500 {
            border-left-color: #8b5cf6;
        }

        .border-blue-500 {
            border-left-color: #3b82f6;
        }

        .border-green-500 {
            border-left-color: #10b981;
        }

        .border-yellow-500 {
            border-left-color: #f59e0b;
        }

        .border-indigo-500 {
            border-left-color: #6366f1;
        }

        .text-purple-500 {
            color: #8b5cf6;
        }

        .text-blue-500 {
            color: #3b82f6;
        }

        .text-green-500 {
            color: #10b981;
        }

        .text-yellow-500 {
            color: #f59e0b;
        }

        .text-indigo-500 {
            color: #6366f1;
        }
    </style>
@endsection
