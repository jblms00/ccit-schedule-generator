$(document).ready(function () {
    displaySchedulesOnCalendar();
});

function displaySchedulesOnCalendar() {
    $.ajax({
        url: "phpscripts/fetch-schedules.php",
        type: "GET",
        dataType: "json",
        success: function (data) {
            if (data.status === "success") {
                var events = [];
                var daysOfWeekMap = {
                    Sunday: 0,
                    Monday: 1,
                    Tuesday: 2,
                    Wednesday: 3,
                    Thursday: 4,
                    Friday: 5,
                    Saturday: 6,
                };

                // Call getLightColors function to get colors
                const lightColors = getLightColors();

                // Map to store colors for sections
                var sectionColors = {};
                var colorIndex = 0; // To keep track of the index in lightColors

                data.schedules.forEach(function (schedule) {
                    // Check if week_days is defined and a string
                    if (
                        schedule.week_days &&
                        typeof schedule.week_days === "string"
                    ) {
                        // Check for "to" in week_days to determine if it's a range
                        var weekDays = schedule.week_days.includes("to")
                            ? getDaysInRange(schedule.week_days.split(" to "))
                            : [schedule.week_days];

                        // Get or assign color to section
                        var section = schedule.section;
                        if (!sectionColors[section]) {
                            sectionColors[section] =
                                lightColors[colorIndex % lightColors.length];
                            colorIndex++; // Increment color index for the next section
                        }

                        // Process each day
                        weekDays.forEach(function (day) {
                            var trimmedDay = day.trim();
                            var dayIndex = daysOfWeekMap[trimmedDay];

                            if (dayIndex !== undefined) {
                                // Check if the day is valid
                                events.push({
                                    id: schedule.schedule_id,
                                    title: `${
                                        schedule.subject_code
                                    } | Section: ${schedule.section} | Room: ${
                                        schedule.room
                                    } | Time: ${formatTime(
                                        schedule.start_time
                                    )} to ${formatTime(schedule.end_time)}`,
                                    startTime: schedule.start_time,
                                    endTime: schedule.end_time,
                                    daysOfWeek: [dayIndex],
                                    color: sectionColors[section], // Use color mapped to section
                                    extendedProps: {
                                        subject_code: schedule.subject_code,
                                        subject_name: schedule.subject_name,
                                        section: schedule.section,
                                        instructor_name:
                                            schedule.instructor_name ||
                                            "Instructor Not Assigned", // Default to "Instructor Not Assigned" if instructor_name is undefined
                                        room: schedule.room,
                                        start_time: formatTime(
                                            schedule.start_time
                                        ),
                                        end_time: formatTime(schedule.end_time),
                                    },
                                });
                            } else {
                                console.error(
                                    `Invalid day '${trimmedDay}' for schedule ID ${schedule.schedule_id}.`
                                );
                            }
                        });
                    } else {
                        console.error(
                            `week_days is undefined or not a string for schedule ID ${schedule.schedule_id}:`,
                            schedule
                        );
                    }
                });

                var calendarEl = document.getElementById("calendar");
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: "dayGridMonth",
                    headerToolbar: {
                        left: "prev,next today",
                        center: "title",
                        right: "",
                    },
                    events: events,
                    eventContent: function (data) {
                        const { subject_code, section } =
                            data.event.extendedProps;
                        return {
                            html: `\
                                <div class="calendar-day" style="background:${data.event.backgroundColor};">\
                                    <p>Subject: ${subject_code}</p>\
                                    <p>Section: ${section}</p>\
                                </div>\
                            `,
                        };
                    },
                    eventClick: function (info) {
                        const {
                            subject_name,
                            section,
                            instructor_name,
                            room,
                            start_time,
                            end_time,
                        } = info.event.extendedProps;

                        document.getElementById("modalSubject").value =
                            subject_name;
                        document.getElementById("modalInstructor").value =
                            instructor_name; // Displaying instructor name or "Instructor Not Assigned"
                        document.getElementById("modalSection").value = section;
                        document.getElementById("modalRoom").value = room;
                        document.getElementById(
                            "modalTime"
                        ).value = `${start_time} to ${end_time}`;

                        $("#scheduleModal").modal("show");
                    },
                });

                calendar.render();
            } else {
                console.error(data.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error fetching schedules: " + error);
        },
    });
}

// Helper function to get all days in range
function getDaysInRange(daysArray) {
    const startDay = daysArray[0].trim();
    const endDay = daysArray[1].trim();

    const daysOfWeekMap = {
        Sunday: 0,
        Monday: 1,
        Tuesday: 2,
        Wednesday: 3,
        Thursday: 4,
        Friday: 5,
        Saturday: 6,
    };

    const startIndex = daysOfWeekMap[startDay];
    const endIndex = daysOfWeekMap[endDay];

    const daysInRange = [];
    for (let i = startIndex; i <= endIndex; i++) {
        for (const [day, index] of Object.entries(daysOfWeekMap)) {
            if (index === i) {
                daysInRange.push(day);
            }
        }
    }
    return daysInRange;
}

function formatTime(time) {
    const [hours, minutes] = time.split(":");
    const hour = parseInt(hours, 10);
    const ampm = hour >= 12 ? "PM" : "AM";
    const formattedHour = hour % 12 || 12;
    return `${formattedHour}:${minutes} ${ampm}`;
}

function getLightColors() {
    return [
        "#8faf99",
        "#d2c7c1",
        "#ffecb3", // light amber
        "#ffe082", // light yellow
        "#dce775", // light lime green
        "#81d4fa", // light sky blue
        "#ff8a80", // light red
        "#ffb74d", // light orange
        "#b39ddb", // light purple
        "#80deea", // light cyan
        "#c5e1a5", // light green
        "#fff9c4", // light butter yellow
        "#b2ebf2", // light cyan
        "#f0f4c3", // light lime
        "#cfd8dc", // light blue grey
        "#d1c4e9", // light purple
        "#f8bbd0", // light pink
        "#ffcc80", // light orange
        "#e0e0e0", // light grey
        "#a5d6a7", // light green
        "#ffcdd2", // light red
        "#ffab40", // light orange
        "#ffccbc", // light orange
        "#d7ccc8", // light brown
        "#f5f5f5", // light grey
        "#c8e6c9", // light green
        "#f0f4c3", // light lime
        "#e1bee7", // light lavender
        "#ffe57f", // light butter
        "#ffb74d", // light amber
        "#90caf9", // light blue
        "#ffccbc", // light orange
        "#f06292", // light pink
        "#c5ca9a", // light green
    ];
}
