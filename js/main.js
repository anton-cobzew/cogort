function formatMessageDateTime(message_datetime) {
  var dt = new Date(message_datetime + ' UTC'),
      dts = dt.toString(),
      now = new Date();
  if (dt.getFullYear() == now.getFullYear() &&
      dt.getMonth() == now.getMonth() &&
      dt.getDate() == now.getDate()) {
    // Message sent today
    return $.format.date(dts, "HH:mm");
  }
  else if (dt.getFullYear() == now.getFullYear())
    // Message sent this year
    return $.format.date(dts, "ddd.MMMM HH:mm");
  else
    return $.format.date(dts, "ddd:MMMM.yy HH:mm");
}
