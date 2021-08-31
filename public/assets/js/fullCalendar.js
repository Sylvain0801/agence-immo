document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    headerToolbar: 
      {
        start: 'prev,next today', 
        center: 'title',
        end: 'dayGridMonth,dayGridWeek listMonth' 
      },
    defaultAllDay: true,
    dayMaxEventRows: 2,
    moreLinkClick: 'popover',
    dayPopoverFormat: { month: 'long', day: 'numeric', year: 'numeric' },
    events: JSON.parse(calendarEl.dataset.datas),
    eventMouseEnter: function(item) { 
      item.el.setAttribute('title', item.event.start.toLocaleString('fr-FR', {year: '2-digit', month: '2-digit', day: '2-digit'}) + '\n' + item.event.title + ' :\n' +  item.event.extendedProps.description)
     },
    eventClick: function(item) {
      const [id, title, description, start, end, colors, repeat, repeatLabel, frequencies, modalTitle] = [
        document.getElementById('calendar_reminder_id'),
        document.getElementById('calendar_title'),
        document.getElementById('calendar_description'),
        document.getElementById('calendar_reminder_date'),
        document.getElementById('calendar_repeat_end'),
        document.querySelectorAll('.input-horizontal-group-colors .input-radio-group input'),
        document.getElementById('calendar_is_repeated'),
        document.querySelector('[for=calendar_repeat_end]'),
        document.querySelectorAll('#repeat-remind-details .input-horizontal-group .input-radio-group input'),
        document.querySelectorAll('.modal-reminder-title')
      ]
      const [editReminder, editReminderAll, deleteReminder, deleteReminderAll, cancelEditReminder] = [
        document.getElementById('edit-reminder'),
        document.getElementById('edit-reminder-all'),
        document.getElementById('delete-reminder'),
        document.getElementById('delete-reminder-all'),
        document.getElementById('cancel-edit-reminder'),
      ]
      const ButtonSubmitGroup = document.getElementById('button-submit-group')
      const setInputValues = () => {
        clearInputValues()
        editReminder.setAttribute('formaction', item.event.extendedProps.pathEdit)
        editReminderAll.setAttribute('formaction', item.event.extendedProps.pathEditAll)
        deleteReminder.setAttribute('formaction', item.event.extendedProps.pathDelete)
        deleteReminderAll.setAttribute('formaction', item.event.extendedProps.pathDeleteAll)
        ButtonSubmitGroup.classList.add('d-none')
        for (const elt of [editReminder, deleteReminder, cancelEditReminder]) elt.classList.remove('d-none')
        if (item.event.extendedProps.isRepeated) [editReminderAll, deleteReminderAll].forEach(elt => elt.classList.remove('d-none'))
        modalTitle.forEach(elt => elt.innerHTML = `<i class="fa fa-calendar"></i>&nbsp;${elt.dataset.titleEdit}`)
        id.value = item.event.id
        title.value = item.event.title
        description.textContent = item.event.extendedProps.description
        start.value = item.event.start.toLocaleString('fr-FR', {year: '2-digit', month: '2-digit', day: '2-digit'});
        for (const color of colors) {
          if (color.value === item.event.backgroundColor) color.checked = true
        }
        const repeatDate = item.event.extendedProps.repeatEnd
        if (repeatDate) {
          end.removeAttribute('disabled')
          end.setAttribute('required', 'required')
          end.value = (new Date(repeatDate.date)).toLocaleString('fr-FR', {year: '2-digit', month: '2-digit', day: '2-digit'})
        }
        repeat.checked = item.event.extendedProps.isRepeated
        const frequency = item.event.extendedProps.frequency
        if (frequency) {
          for (const freq of frequencies) {
            freq.removeAttribute('disabled')
            freq.setAttribute('required', 'required')
            if (freq.value === frequency) freq.checked = true
          }
          repeatLabel.setAttribute('style', 'color: #33404A')
        }
      }
      const clearInputValues = () => {
        ButtonSubmitGroup.classList.remove('d-none')
        for (const elt of [editReminder, editReminderAll, deleteReminder, deleteReminderAll, cancelEditReminder]) {
          elt.classList.add('d-none')
          elt.removeAttribute('formaction')
        }
        modalTitle.forEach(elt => elt.innerHTML = `<i class="fa fa-calendar"></i>&nbsp;${elt.dataset.titleAdd}`)
        title.value = ''
        description.textContent = ''
        start.value = ''
        repeat.checked = false
        colors.forEach(elt => elt.checked = false)
        end.removeAttribute('required')
        end.setAttribute('disabled', 'disabled')
        end.value = ''
        for (const freq of frequencies) {
          freq.removeAttribute('required')
          freq.setAttribute('disabled', 'disabled')
          freq.checked = false
        }
        repeatLabel.setAttribute('style', 'color: #CCCCCC')
      }
      setInputValues()
      let modal = document.querySelector('#modal-add-date');
      setTimeout(() => modal.classList.add("show"), 200);
      const modalClose = modal.querySelectorAll("[data-dismiss=dialog]");
      for (let close of modalClose) {
        close.addEventListener("click", (e) => {
        e.preventDefault()
        modal.classList.remove("show");
        clearInputValues()
        });
      }
      modal.addEventListener("click", function() { 
        this.classList.remove("show")
        clearInputValues()
      })
      modal.children[0].addEventListener("click", function(e) { e.stopPropagation() });
    }
  });
  calendar.setOption('locale', calendarEl.dataset.locale);
  calendar.render();
});