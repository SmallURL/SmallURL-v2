#
# Part of the SmallURL Network!
# Put me into the scripts dir of textual
# Don't blame DaveT if this doesnt work :p
#
to splitString(aString, delimiter)
	set retVal to {}
	set prevDelimiter to AppleScript's text item delimiters
	log delimiter
	set AppleScript's text item delimiters to {delimiter}
	set retVal to every text item of aString
	set AppleScript's text item delimiters to prevDelimiter
	return retVal
end splitString


on textualcmd(input)
	set shorturl to "Shorten Failed."
	set curlCMD to "curl http://api.smallurl.in/v1 -L -G -d \"type=simple&url=" & input & "\""
	
	tell me to set shorturl to (do shell script curlCMD)
	
	if shorturl = "false" then
		#Dismiss textual returning random stuff.
		
		#Return shortened URL
	else
		set smallstring to splitString(shorturl, "|")
		set smallstring to item 2 of smallstring
		set smallstring to splitString(smallstring, "short=")
		set smallstring to item 2 of smallstring
		return "http://surl.im/" & smallstring
	end if
end textualcmd