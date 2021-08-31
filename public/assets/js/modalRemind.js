window.onload = () => {

  const repeatRemindDetails = document.getElementById('repeat-remind-details')
  const checkboxRepeatRemind = document.getElementById('calendar_is_repeated')
  const inputsFrequency = document.querySelectorAll('#repeat-remind-details .input-radio-group input')
  const endRepeatRemindDate = repeatRemindDetails.querySelector(' [for=calendar_repeat_end]')
  const inputCalendarRepeatEnd = document.getElementById('calendar_repeat_end')
  const headerColors = document.querySelectorAll('.modal-header-color')
  headerColors.forEach(elt => elt.addEventListener('click', (e) => e.preventDefault()))
  checkboxRepeatRemind.addEventListener('click', function(e) {
    if (this.checked) {
      [inputCalendarRepeatEnd, ...inputsFrequency].forEach(elt => {
        elt.removeAttribute('disabled')
        elt.setAttribute('required', 'required')
      })
      endRepeatRemindDate.setAttribute('style', 'color: #33404A')
    } else {
      [inputCalendarRepeatEnd, ...inputsFrequency].forEach(elt => {
        elt.removeAttribute('required')
        elt.checked = false
        elt.setAttribute('disabled', 'disabled')
      })
      inputCalendarRepeatEnd.value = ''
      endRepeatRemindDate.setAttribute('style', 'color: #CCCCCC')
    }
  })
}