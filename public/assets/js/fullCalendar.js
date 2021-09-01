document.addEventListener('DOMContentLoaded', function() {
  const dateFormat = {year: '2-digit', month: '2-digit', day: '2-digit'}
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
      item.el.setAttribute('title', item.event.start.toLocaleString('fr-FR', dateFormat) + '\n' + item.event.title + ' :\n' +  item.event.extendedProps.description)
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
      const props = item.event.extendedProps

      // Renseigne tous les inputs du formulaire lorsqu'on édite un rappel
      const setInputValues = () => {
        clearInputValues()
        editReminder.setAttribute('formaction', props.path.edit)
        editReminderAll.setAttribute('formaction', props.path.editAll)
        deleteReminder.setAttribute('data-path', props.path.delete)
        deleteReminderAll.setAttribute('data-path', props.path.deleteAll)
        ButtonSubmitGroup.classList.add('d-none')
        for (const elt of [editReminder, deleteReminder, cancelEditReminder]) elt.classList.remove('d-none')
        if (props.isRepeated) [editReminderAll, deleteReminderAll].forEach(elt => elt.classList.remove('d-none'))
        modalTitle.forEach(elt => elt.innerHTML = `<i class="fa fa-calendar"></i>&nbsp;${elt.dataset.titleEdit}`)
        id.value = item.event.id
        title.value = item.event.title
        description.textContent = props.description
        start.value = item.event.start.toLocaleString('fr-FR', dateFormat);
        for (const color of colors) {
          if (color.value === item.event.backgroundColor) color.checked = true
        }
        const repeatDate = props.repeatEnd
        if (repeatDate) {
          end.removeAttribute('disabled')
          end.setAttribute('required', 'required')
          end.value = (new Date(repeatDate.date)).toLocaleString('fr-FR', dateFormat)
        }
        repeat.checked = props.isRepeated
        const frequency = props.frequency
        if (frequency) {
          for (const freq of frequencies) {
            freq.removeAttribute('disabled')
            freq.setAttribute('required', 'required')
            if (freq.value === frequency) freq.checked = true
          }
          repeatLabel.setAttribute('style', 'color: #33404A')
        }
      }

      // Réinitialise tous les inputs du formulaire d'ajout et modif de rappel
      const clearInputValues = () => {
        ButtonSubmitGroup.classList.remove('d-none')
        for (const elt of [editReminder, editReminderAll, deleteReminderAll, cancelEditReminder]) {
          elt.classList.add('d-none')
          elt.removeAttribute('formaction')
        }
        deleteReminder.removeAttribute('data-path')
        deleteReminderAll.removeAttribute('data-path')
        modalTitle.forEach(elt => elt.innerHTML = `<i class="fa fa-calendar"></i>&nbsp;${elt.dataset.titleAdd}`)
        title.value = description.textContent = start.value = ''
        for (const elt of [repeat, ...colors]) elt.checked = false
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

      // Gestion ouverture fermeture de la modale du formulaire
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