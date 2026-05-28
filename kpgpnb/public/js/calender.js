class ChurchCalendar {
    constructor(containerId, options = {}) {
        this.container = document.getElementById(containerId);
        this.currentDate = new Date();
        this.events = options.events || [];
        this.view = options.view || 'month';
        
        this.init();
    }
    
    init() {
        this.render();
        this.bindEvents();
    }
    
    render() {
        this.container.innerHTML = this.generateCalendarHTML();
    }
    
    generateCalendarHTML() {
        return `
            <div class="calendar-container">
                ${this.generateHeader()}
                ${this.generateFilters()}
                ${this.generateViewOptions()}
                ${this.generateCalendarGrid()}
                ${this.generateLegend()}
            </div>
            ${this.generateEventModal()}
        `;
    }
    
    generateHeader() {
        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                           "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        
        const currentMonth = monthNames[this.currentDate.getMonth()];
        const currentYear = this.currentDate.getFullYear();
        
        return `
            <div class="calendar-header">
                <div class="calendar-title">
                    <i class="calendar-icon">📅</i>
                    Kalender Kegiatan Gereja
                </div>
                <div class="calendar-nav">
                    <button class="calendar-nav-btn" data-action="prev">←</button>
                    <div class="calendar-current">${currentMonth} ${currentYear}</div>
                    <button class="calendar-nav-btn" data-action="next">→</button>
                    <button class="calendar-nav-btn" data-action="today">Hari Ini</button>
                </div>
            </div>
        `;
    }
    
    generateFilters() {
        return `
            <div class="calendar-filters">
                <div class="filter-group">
                    <span class="filter-label">Filter Kegiatan:</span>
                    <label class="filter-checkbox">
                        <input type="checkbox" data-type="ibadah" checked>
                        <span class="filter-color ibadah"></span>
                        Ibadah
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" data-type="pelayanan" checked>
                        <span class="filter-color pelayanan"></span>
                        Pelayanan
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" data-type="pemuda" checked>
                        <span class="filter-color pemuda"></span>
                        Pemuda
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" data-type="khusus" checked>
                        <span class="filter-color khusus"></span>
                        Khusus
                    </label>
                </div>
            </div>
        `;
    }
    
    generateViewOptions() {
        return `
            <div class="calendar-view-options">
                <button class="view-option-btn ${this.view === 'month' ? 'active' : ''}" data-view="month">
                    Bulanan
                </button>
                <button class="view-option-btn ${this.view === 'week' ? 'active' : ''}" data-view="week">
                    Mingguan
                </button>
                <button class="view-option-btn ${this.view === 'list' ? 'active' : ''}" data-view="list">
                    Daftar
                </button>
            </div>
        `;
    }
    
    generateCalendarGrid() {
        if (this.view === 'month') {
            return this.generateMonthView();
        } else if (this.view === 'week') {
            return this.generateWeekView();
        } else {
            return this.generateListView();
        }
    }
    
    generateMonthView() {
        const firstDay = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), 1);
        const lastDay = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 0);
        const startDate = new Date(firstDay);
        startDate.setDate(startDate.getDate() - firstDay.getDay());
        
        const endDate = new Date(lastDay);
        endDate.setDate(endDate.getDate() + (6 - lastDay.getDay()));
        
        let gridHTML = `
            <div class="calendar-grid calendar-month">
                <div class="calendar-day-header">Minggu</div>
                <div class="calendar-day-header">Senin</div>
                <div class="calendar-day-header">Selasa</div>
                <div class="calendar-day-header">Rabu</div>
                <div class="calendar-day-header">Kamis</div>
                <div class="calendar-day-header">Jumat</div>
                <div class="calendar-day-header">Sabtu</div>
        `;
        
        const currentDate = new Date(startDate);
        while (currentDate <= endDate) {
            const isToday = this.isToday(currentDate);
            const isCurrentMonth = currentDate.getMonth() === this.currentDate.getMonth();
            const isSunday = currentDate.getDay() === 0;
            
            const dateEvents = this.getEventsForDate(currentDate);
            
            gridHTML += `
                <div class="calendar-cell ${!isCurrentMonth ? 'other-month' : ''} ${isToday ? 'today' : ''} ${isSunday ? 'sunday' : ''}">
                    <div class="calendar-date">${currentDate.getDate()}</div>
                    <div class="calendar-events">
                        ${dateEvents.slice(0, 3).map(event => this.generateEventHTML(event)).join('')}
                        ${dateEvents.length > 3 ? `
                            <div class="event-more">+${dateEvents.length - 3} lebih</div>
                        ` : ''}
                    </div>
                </div>
            `;
            
            currentDate.setDate(currentDate.getDate() + 1);
        }
        
        gridHTML += `</div>`;
        return gridHTML;
    }
    
    generateEventHTML(event) {
        const isPast = new Date(event.end_date) < new Date();
        return `
            <div class="calendar-event ${event.kategori} ${isPast ? 'past' : ''}" 
                 data-event-id="${event.id}">
                <div class="event-time">${event.waktu}</div>
                <div class="event-title">${event.nama_kegiatan}</div>
            </div>
        `;
    }
    
    generateLegend() {
        return `
            <div class="calendar-legend">
                <div class="legend-item">
                    <span class="legend-color ibadah"></span>
                    <span>Ibadah</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color pelayanan"></span>
                    <span>Pelayanan</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color pemuda"></span>
                    <span>Pemuda</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color khusus"></span>
                    <span>Khusus</span>
                </div>
            </div>
        `;
    }
    
    generateEventModal() {
        return `
            <div class="event-modal" id="eventModal">
                <div class="event-modal-content">
                    <button class="event-modal-close">&times;</button>
                    <div class="event-modal-header">
                        <div class="event-modal-title" id="eventModalTitle"></div>
                        <div class="event-modal-date" id="eventModalDate"></div>
                    </div>
                    <div class="event-modal-body" id="eventModalBody"></div>
                </div>
            </div>
        `;
    }
    
    bindEvents() {
        // Navigation
        this.container.addEventListener('click', (e) => {
            const button = e.target.closest('[data-action]');
            if (button) {
                const action = button.dataset.action;
                this.handleAction(action);
            }
            
            // View options
            const viewButton = e.target.closest('[data-view]');
            if (viewButton) {
                this.view = viewButton.dataset.view;
                this.render();
            }
            
            // Event clicks
            const eventElement = e.target.closest('.calendar-event');
            if (eventElement) {
                const eventId = eventElement.dataset.eventId;
                this.showEventDetails(eventId);
            }
        });
        
        // Filters
        this.container.addEventListener('change', (e) => {
            if (e.target.type === 'checkbox') {
                this.applyFilters();
            }
        });
        
        // Modal close
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('event-modal-close') || 
                e.target.classList.contains('event-modal')) {
                this.hideEventModal();
            }
        });
    }
    
    handleAction(action) {
        switch (action) {
            case 'prev':
                if (this.view === 'month') {
                    this.currentDate.setMonth(this.currentDate.getMonth() - 1);
                } else {
                    this.currentDate.setDate(this.currentDate.getDate() - 7);
                }
                break;
            case 'next':
                if (this.view === 'month') {
                    this.currentDate.setMonth(this.currentDate.getMonth() + 1);
                } else {
                    this.currentDate.setDate(this.currentDate.getDate() + 7);
                }
                break;
            case 'today':
                this.currentDate = new Date();
                break;
        }
        this.render();
    }
    
    getEventsForDate(date) {
        return this.events.filter(event => {
            const eventDate = new Date(event.tanggal);
            return eventDate.toDateString() === date.toDateString();
        });
    }
    
    isToday(date) {
        const today = new Date();
        return date.toDateString() === today.toDateString();
    }
    
    showEventDetails(eventId) {
        const event = this.events.find(e => e.id == eventId);
        if (!event) return;
        
        const modal = document.getElementById('eventModal');
        const title = document.getElementById('eventModalTitle');
        const date = document.getElementById('eventModalDate');
        const body = document.getElementById('eventModalBody');
        
        title.textContent = event.nama_kegiatan;
        date.textContent = this.formatEventDate(event);
        body.innerHTML = this.generateEventDetailsHTML(event);
        
        modal.classList.add('active');
    }
    
    hideEventModal() {
        const modal = document.getElementById('eventModal');
        modal.classList.remove('active');
    }
    
    formatEventDate(event) {
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        const date = new Date(event.tanggal);
        return date.toLocaleDateString('id-ID', options) + ' • ' + event.waktu;
    }
    
    generateEventDetailsHTML(event) {
        return `
            <div class="event-detail">
                <div class="event-detail-label">Jenis Kegiatan</div>
                <div class="event-detail-value">
                    <span class="event-badge ${event.kategori}">${event.kategori}</span>
                </div>
            </div>
            <div class="event-detail">
                <div class="event-detail-label">Waktu</div>
                <div class="event-detail-value">${event.waktu}</div>
            </div>
            <div class="event-detail">
                <div class="event-detail-label">Tempat</div>
                <div class="event-detail-value">${event.tempat}</div>
            </div>
            <div class="event-detail">
                <div class="event-detail-label">Pemimpin</div>
                <div class="event-detail-value">${event.pemimpin}</div>
            </div>
            <div class="event-detail">
                <div class="event-detail-label">Keterangan</div>
                <div class="event-detail-value">${event.keterangan}</div>
            </div>
        `;
    }
    
    applyFilters() {
        const checkboxes = this.container.querySelectorAll('input[type="checkbox"]');
        const enabledTypes = Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.dataset.type);
        
        // Implement filter logic here
        console.log('Enabled types:', enabledTypes);
    }
}

// Initialize calendar when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Example events data - in real application, this would come from PHP/CodeIgniter
    const sampleEvents = [
        {
            id: 1,
            nama_kegiatan: "Ibadah Minggu Pagi",
            kategori: "ibadah",
            tanggal: "2024-01-14",
            waktu: "07:00 - 09:00",
            tempat: "Gedung Gereja",
            pemimpin: "Pdt. John Doe",
            keterangan: "Ibadah umum untuk seluruh jemaat"
        },
        {
            id: 2,
            nama_kegiatan: "Persekutuan Pemuda",
            kategori: "pemuda",
            tanggal: "2024-01-15",
            waktu: "19:00 - 21:00",
            tempat: "Aula Pemuda",
            pemimpin: "Sdr. Michael",
            keterangan: "Persekutuan pemuda gereja"
        }
    ];
    
    window.churchCalendar = new ChurchCalendar('calendar', {
        events: sampleEvents,
        view: 'month'
    });
});