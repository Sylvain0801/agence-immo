// ####################################################################
// #                                                                  #
// #                Table responsive mobile version                   #
// #                                                                  #
// ####################################################################
document.querySelectorAll('.table-responsive-line').forEach(function (table) {
  let labels = Array.from(table.querySelectorAll('th')).map(function (th) {
      return th.innerText
  })
  table.querySelectorAll('td').forEach(function (td, i) {
      td.setAttribute('data-label', labels[i % labels.length])
  })
})
