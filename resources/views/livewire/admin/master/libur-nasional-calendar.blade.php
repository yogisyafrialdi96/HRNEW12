<div class="flex flex-col gap-6" wire:id="{{ $this->getId() }}">
    <!-- Header Section -->
    <div class="flex flex-col gap-2">
        <div class="flex justify-between items-start md:items-center gap-4 flex-wrap">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">Libur Nasional</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Manajemen libur nasional, lokal, dan cuti bersama</p>
            </div>
            <button
                wire:click="openModal"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-lg font-medium transition inline-flex items-center gap-2 whitespace-nowrap"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Libur
            </button>
        </div>

        <!-- Legend -->
        <div class="grid grid-cols-3 gap-4 text-sm pt-2">
            <div class="flex items-center gap-2 bg-blue-50 dark:bg-blue-900/20 px-3 py-2 rounded">
                <div class="w-3 h-3 rounded" style="background-color: #2563eb;"></div>
                <span class="text-zinc-700 dark:text-zinc-300">Nasional</span>
            </div>
            <div class="flex items-center gap-2 bg-purple-50 dark:bg-purple-900/20 px-3 py-2 rounded">
                <div class="w-3 h-3 rounded" style="background-color: #a855f7;"></div>
                <span class="text-zinc-700 dark:text-zinc-300">Lokal</span>
            </div>
            <div class="flex items-center gap-2 bg-orange-50 dark:bg-orange-900/20 px-3 py-2 rounded">
                <div class="w-3 h-3 rounded" style="background-color: #f97316;"></div>
                <span class="text-zinc-700 dark:text-zinc-300">Cuti Bersama</span>
            </div>
        </div>
    </div>

    <!-- Calendar Container -->
    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-lg border border-zinc-200 dark:border-zinc-700 p-4 md:p-6 overflow-hidden">
        <div id="calendar" wire:ignore class="fc-custom-wrapper"></div>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 p-4" wire:click.self="closeModal">
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-2xl max-w-md w-full">
                <div class="border-b border-zinc-200 dark:border-zinc-700 px-5 py-4 flex justify-between items-center">
                    <h2 class="text-base font-semibold text-zinc-900 dark:text-white">
                        {{ $isEditMode ? 'Edit' : 'Tambah' }} Libur
                    </h2>
                    <button
                        wire:click="closeModal"
                        type="button"
                        class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 transition"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-5 space-y-4">
                    <!-- Nama Libur -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-900 dark:text-white mb-1">
                            Nama Libur <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model="nama_libur"
                            placeholder="Cth: Hari Raya Idul Fitri"
                            class="w-full px-3 py-2 text-sm border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        />
                        @error('nama_libur')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Tanggal Mulai & Akhir -->
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-900 dark:text-white mb-1">
                                Mulai <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                wire:model="tanggal_libur"
                                class="w-full px-3 py-2 text-sm border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            />
                            @error('tanggal_libur')
                                <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-900 dark:text-white mb-1">
                                Akhir
                            </label>
                            <input
                                type="date"
                                wire:model="tanggal_libur_akhir"
                                class="w-full px-3 py-2 text-sm border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            />
                        </div>
                    </div>

                    <!-- Tipe & Provinsi -->
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-900 dark:text-white mb-1">
                                Tipe <span class="text-red-500">*</span>
                            </label>
                            <select
                                wire:model.live="tipe"
                                class="w-full px-3 py-2 text-sm border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            >
                                <option value="nasional">Nasional</option>
                                <option value="lokal">Lokal</option>
                                <option value="cuti_bersama">Cuti Bersama</option>
                            </select>
                        </div>
                        @if($tipe === 'lokal')
                            <div>
                                <label class="block text-xs font-semibold text-zinc-900 dark:text-white mb-1">
                                    Provinsi <span class="text-red-500">*</span>
                                </label>
                                <select
                                    wire:model="provinsi_id"
                                    class="w-full px-3 py-2 text-sm border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                >
                                    <option value="">Pilih Provinsi</option>
                                    @foreach($provinsis as $prov)
                                        <option value="{{ $prov->id }}">{{ $prov->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>

                    <!-- Status Aktif -->
                    <div class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            wire:model="is_active"
                            id="is_active"
                            class="w-4 h-4 rounded border-zinc-300 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:bg-zinc-700 dark:border-zinc-600"
                        />
                        <label for="is_active" class="text-xs font-medium text-zinc-700 dark:text-zinc-300">Aktif</label>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label class="block text-xs font-semibold text-zinc-900 dark:text-white mb-1">
                            Keterangan
                        </label>
                        <textarea
                            wire:model="keterangan"
                            rows="2"
                            placeholder="Optional"
                            class="w-full px-3 py-2 text-sm border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"
                        ></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 pt-3 border-t border-zinc-200 dark:border-zinc-700">
                        <button
                            wire:click="closeModal"
                            type="button"
                            class="flex-1 px-3 py-2 text-sm border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-700 font-medium transition"
                        >
                            Batal
                        </button>
                        @if($isEditMode)
                            <button
                                wire:click="delete({{ $libur_id }})"
                                wire:confirm="Yakin ingin menghapus libur ini?"
                                type="button"
                                class="px-3 py-2 text-sm bg-red-600 hover:bg-red-700 active:bg-red-800 text-white rounded-lg font-medium transition"
                            >
                                Hapus
                            </button>
                        @endif
                        <button
                            wire:click="save"
                            type="button"
                            class="flex-1 px-3 py-2 text-sm bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-lg font-medium transition"
                        >
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- FullCalendar CSS - Must load before JS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.css' rel='stylesheet' />

    <!-- FullCalendar JS Libraries -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/locales/id.global.min.js'></script>

    <!-- FullCalendar Initialization Script -->
    <script>
        window.liburNasionalCalendar = {
            calendar: null,

            updateEvents: function(newEvents) {
                
                if (!this.calendar) {
                    return;
                }

                try {
                    // Hapus semua events dari calendar
                    const allEvents = this.calendar.getEvents();
                    allEvents.forEach(event => event.remove());

                    // Tambah events baru
                    if (newEvents && newEvents.length > 0) {
                        newEvents.forEach(eventData => {
                            this.calendar.addEvent(eventData);
                        });
                    }
                } catch (error) {
                    // Fallback: reinit entire calendar
                    this.init();
                }
            },

            init: function() {
                const calendarEl = document.getElementById('calendar');
                if (!calendarEl) {
                    return;
                }

                // Destroy existing calendar properly
                if (this.calendar) {
                    try {
                        this.calendar.destroy();
                    } catch (e) {
                        // Cleanup silently
                    }
                    this.calendar = null;
                }

                // Get events from the page's data attribute or from Livewire
                const events = @json($events) || [];

                try {
                    this.calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        locale: 'id',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,listWeek'
                        },
                        contentHeight: 'auto',
                        events: events,
                        eventClick: (info) => {
                            const eventId = parseInt(info.event.id);
                            @this.call('edit', eventId);
                        },
                        dayMaxEvents: 3,
                        editable: true,
                        eventDrop: (info) => {
                            // Event has been dragged to a new date
                            const eventId = parseInt(info.event.id);
                            const newStart = info.event.start.toISOString().split('T')[0];
                            const newEnd = info.event.end ? info.event.end.toISOString().split('T')[0] : null;
                            
                            // Call Livewire method to update the event
                            @this.call('updateEventDates', eventId, newStart, newEnd);
                        },
                        eventResizeStop: (info) => {
                            // Event has been resized
                            const eventId = parseInt(info.event.id);
                            const newStart = info.event.start.toISOString().split('T')[0];
                            const newEnd = info.event.end ? info.event.end.toISOString().split('T')[0] : null;
                            
                            // Call Livewire method to update the event
                            @this.call('updateEventDates', eventId, newStart, newEnd);
                        },
                        dateClick: (info) => {
                            @this.set('tanggal_libur', info.dateStr);
                            @this.call('openModal');
                        },
                        buttonText: {
                            today: 'Hari Ini',
                            month: 'Bulan',
                            week: 'Minggu',
                            list: 'List'
                        }
                    });

                    this.calendar.render();
                } catch (error) {
                    // Error handling silently
                }
            }
        };

        // Initialize when libraries and DOM are ready
        (function initializeOnReady() {
            if (typeof FullCalendar === 'undefined') {
                setTimeout(initializeOnReady, 100);
                return;
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    window.liburNasionalCalendar.init();
                });
            } else {
                window.liburNasionalCalendar.init();
            }
        })();

        // Handle Livewire component lifecycle
        if (typeof Livewire !== 'undefined') {
            // Before component updates, we need to preserve calendar if possible
            Livewire.hook('component.updating', () => {
                // Component updating...
            });

            // After component updates
            Livewire.hook('component.updated', ({ component }) => {
                // Check if this is the LiburNasionalIndex component
                if (!component.fingerprint.name.includes('LiburNasionalIndex')) {
                    return;
                }
                
                // Delay to ensure DOM is updated with new events
                setTimeout(() => {
                    if (!window.liburNasionalCalendar.calendar) {
                        window.liburNasionalCalendar.init();
                    } else {
                        // Re-fetch fresh events from the updated Blade view
                        // We'll trigger a full re-initialization to be safe
                        window.liburNasionalCalendar.init();
                    }
                }, 100);
            });
        }
    </script>

    <!-- FullCalendar Custom Styles -->
    <style>
        .fc-custom-wrapper {
            min-height: 700px;
            width: 100%;
        }

        /* Main calendar styling */
        .fc {
            font-family: inherit;
            --fc-border-color: #e4e4e7;
            --fc-button-bg-color: #2563eb;
            --fc-button-border-color: #2563eb;
            --fc-button-hover-bg-color: #1d4ed8;
            --fc-button-hover-border-color: #1d4ed8;
            --fc-button-active-bg-color: #1e40af;
            --fc-button-active-border-color: #1e40af;
            --fc-today-bg-color: rgba(37, 99, 235, 0.1);
        }

        .fc .fc-button-primary {
            background-color: #2563eb !important;
            border-color: #2563eb !important;
        }

        .fc .fc-button-primary:hover {
            background-color: #1d4ed8 !important;
            border-color: #1d4ed8 !important;
        }

        .fc .fc-button-primary.fc-button-active,
        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background-color: #1e40af !important;
            border-color: #1e40af !important;
        }

        .fc .fc-button-primary:disabled {
            opacity: 0.5;
        }

        .fc .fc-col-header-cell {
            padding: 12px 0 !important;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .fc .fc-daygrid-day {
            min-height: 100px;
        }

        .fc .fc-daygrid-day-number {
            padding: 8px 4px;
        }

        .fc .fc-event {
            border-radius: 4px;
            border: none !important;
            font-size: 0.8rem;
            cursor: pointer;
        }

        .fc .fc-event-title {
            white-space: normal;
            overflow: visible;
            padding: 0 4px;
            font-weight: 500;
        }

        .fc a {
            color: inherit;
            text-decoration: none;
        }

        .fc a:hover {
            text-decoration: underline;
        }

        /* Light mode */
        .fc {
            background-color: #ffffff;
            color: #1f2937;
        }

        .fc .fc-col-header-cell {
            background-color: #f3f4f6;
            color: #1f2937;
            border-color: #e5e7eb;
        }

        .fc .fc-daygrid-day {
            background-color: #ffffff;
            border-color: #e5e7eb;
        }

        .fc .fc-daygrid-day.fc-day-other {
            background-color: #fafafa;
        }

        .fc .fc-daygrid-day.fc-day-today {
            background-color: #eff6ff;
        }

        .fc .fc-daygrid-day:hover {
            background-color: #f9fafb;
        }

        /* Dark mode */
        :root.dark .fc,
        .dark .fc {
            background-color: #18181b;
            color: #e4e4e7;
            --fc-border-color: #3f3f46;
            --fc-text-color: #fafafa;
        }

        :root.dark .fc .fc-col-header-cell,
        .dark .fc .fc-col-header-cell {
            background-color: #27272a;
            color: #fafafa;
            border-color: #3f3f46;
        }

        :root.dark .fc .fc-daygrid-day,
        .dark .fc .fc-daygrid-day {
            background-color: #18181b;
            border-color: #3f3f46;
        }

        :root.dark .fc .fc-daygrid-day.fc-day-other,
        .dark .fc .fc-daygrid-day.fc-day-other {
            background-color: #0f0f0f;
        }

        :root.dark .fc .fc-daygrid-day.fc-day-today,
        .dark .fc .fc-daygrid-day.fc-day-today {
            background-color: rgba(37, 99, 235, 0.15);
        }

        :root.dark .fc .fc-daygrid-day:hover,
        .dark .fc .fc-daygrid-day:hover {
            background-color: #27272a;
        }

        /* Navigation buttons */
        .fc .fc-toolbar-title {
            font-size: 1.4rem;
            font-weight: 700;
        }

        .fc .fc-button-group {
            gap: 4px !important;
        }

        /* View buttons */
        .fc .fc-view-harness {
            border: none;
        }

        .fc .fc-daygrid-day-frame {
            position: relative;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .fc .fc-toolbar {
                flex-direction: column;
                gap: 12px;
            }

            .fc .fc-toolbar-chunk {
                width: 100%;
                text-align: center;
            }

            .fc .fc-button-group {
                flex-wrap: wrap;
                justify-content: center;
            }

            .fc .fc-button {
                padding: 0.4em 0.6em;
                font-size: 0.8rem;
            }

            .fc .fc-col-header-cell {
                padding: 8px 2px !important;
                font-size: 0.75rem;
            }

            .fc .fc-daygrid-day {
                min-height: 70px;
            }

            .fc .fc-event-title {
                font-size: 0.65rem;
            }
        }
    </style>
</div>
