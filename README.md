# simple tool to send & validate mail (mailgun account required)


**usage:**

first:   
fill the blank;

then:  
$mailgun = new Mailgun();

**send mail:**  
$mailgun->send($mail, $subject, $content);

**validate mail:**  
$mailgun->validate($mail);

